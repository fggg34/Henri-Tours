<?php

namespace App\Console\Commands;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportBlogPostsFromCsv extends Command
{
    protected $signature = 'blog:import-csv
                            {csv : Path to the Posts export CSV file}
                            {--flush : Delete all existing posts before importing}
                            {--dry-run : Show what would be imported without writing}';

    protected $description = 'Import blog posts from CSV. Maps: categories, featured image, title, content, tags.';

    /** @var array<string, int> */
    private array $categoryMap = [];

    /** @var array<string, int> */
    private array $tagMap = [];

    public function handle(): int
    {
        set_time_limit(0);
        if (ini_get('memory_limit') !== '-1') {
            @ini_set('memory_limit', '512M');
        }

        $path = $this->argument('csv');

        if (! is_file($path) || ! is_readable($path)) {
            $this->error("File not found or not readable: {$path}");
            return self::FAILURE;
        }

        set_time_limit(3600);
        ini_set('memory_limit', '512M');

        $dryRun = (bool) $this->option('dry-run');
        $flush = (bool) $this->option('flush');

        if ($dryRun) {
            $this->warn('DRY RUN - no data will be written.');
        }

        $user = User::first();
        if (! $user) {
            $this->error('No user found. Create an admin user first.');
            return self::FAILURE;
        }

        if ($flush && ! $dryRun) {
            $count = BlogPost::count();
            BlogPost::query()->delete();
            $this->info("Deleted {$count} existing posts.");
        } elseif ($flush && $dryRun) {
            $this->line('  Would delete ' . BlogPost::count() . ' existing posts.');
        }

        $this->buildCategoryMap();
        $this->buildTagMap();

        $handle = fopen($path, 'r');
        if ($handle === false) {
            $this->error('Could not open CSV.');
            return self::FAILURE;
        }

        $header = fgetcsv($handle, 0, ',', '"', '');
        if ($header === false) {
            fclose($handle);
            $this->error('Empty or invalid CSV.');
            return self::FAILURE;
        }

        $colMap = [];
        foreach ($header as $i => $col) {
            $col = trim($col, "\xEF\xBB\xBF\"");
            if (! isset($colMap[$col])) {
                $colMap[$col] = $i;
            }
        }

        $imported = 0;
        $skipped = 0;
        $rowNum = 1;

        while (($row = fgetcsv($handle, 0, ',', '"', '')) !== false) {
            $rowNum++;
            $get = fn (string $name) => isset($colMap[$name]) ? trim($row[$colMap[$name]] ?? '') : '';

            $title = $get('Title');
            if (empty($title)) {
                $skipped++;
                continue;
            }

            $content = $get('Content');
            $excerpt = $get('Excerpt');
            $dateStr = $get('Date');
            $permalink = $get('Permalink');

            $featuredUrl = $get('Image URL');
            if (empty($featuredUrl)) {
                $featuredUrl = $get('Attachment URL');
            }
            if (empty($featuredUrl) && isset($colMap['Image Featured'])) {
                $featuredUrl = $get('Image Featured');
            }

            $categoriesStr = $get('Categories');
            $tagsStr = $get('Tags');

            $slug = $this->parseSlug($permalink, $title);

            $publishedAt = null;
            if (! empty($dateStr)) {
                try {
                    $dt = Carbon::parse($dateStr);
                    if ($dt) {
                        $publishedAt = $dt;
                    }
                } catch (\Throwable) {
                    // ignore
                }
            }
            if (! $publishedAt) {
                $publishedAt = now();
            }

            $categoryId = $this->resolveOrCreateCategoryId($categoriesStr, $dryRun);
            $tagIds = $this->resolveOrCreateTagIds($tagsStr, $dryRun);

            if ($dryRun) {
                $this->line("  Would import: [{$slug}] {$title}");
                $imported++;
                continue;
            }

            try {
                $post = BlogPost::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'blog_category_id' => $categoryId,
                        'user_id' => $user->id,
                        'title' => $title,
                        'excerpt' => $excerpt ?: null,
                        'content' => $content ?: null,
                        'featured_image' => $featuredUrl ?: null,
                        'meta_title' => $title,
                        'meta_description' => $excerpt ? Str::limit(strip_tags($excerpt), 160) : null,
                        'is_published' => true,
                        'published_at' => $publishedAt,
                    ]
                );
                $post->tags()->sync($tagIds);
                $this->line("  Imported: [{$slug}] {$title}");
                $imported++;
            } catch (\Throwable $e) {
                $this->error("  Error at row {$rowNum}: {$e->getMessage()}");
            }
        }

        fclose($handle);

        $this->newLine();
        $this->info("Imported: {$imported} | Skipped: {$skipped}");

        return self::SUCCESS;
    }

    private function parseSlug(string $permalink, string $title): string
    {
        if (empty($permalink)) {
            return Str::slug($title);
        }
        $path = parse_url($permalink, PHP_URL_PATH);
        if ($path) {
            $slug = trim($path, '/');
            $slug = basename($slug);
            if (! empty($slug)) {
                return $slug;
            }
        }
        return Str::slug($title);
    }

    private function buildCategoryMap(): void
    {
        foreach (BlogCategory::all() as $cat) {
            $this->categoryMap[strtolower($cat->name)] = $cat->id;
            $this->categoryMap[strtolower($cat->slug)] = $cat->id;
        }
    }

    private function buildTagMap(): void
    {
        foreach (BlogTag::all() as $tag) {
            $this->tagMap[strtolower($tag->name)] = $tag->id;
            $this->tagMap[strtolower($tag->slug)] = $tag->id;
        }
    }

    private function resolveOrCreateCategoryId(?string $categoriesStr, bool $dryRun): ?int
    {
        if (empty($categoriesStr)) {
            return null;
        }
        $names = $this->parseList($categoriesStr);
        $first = $names[0] ?? null;
        if (empty($first)) {
            return null;
        }
        $key = strtolower(trim($first));
        if (isset($this->categoryMap[$key])) {
            return $this->categoryMap[$key];
        }
        if (! $dryRun) {
            $cat = BlogCategory::firstOrCreate(
                ['slug' => Str::slug($first)],
                ['name' => $first, 'description' => null]
            );
            $this->categoryMap[$key] = $cat->id;
            $this->categoryMap[strtolower($cat->slug)] = $cat->id;
            return $cat->id;
        }
        return BlogCategory::first()?->id;
    }

    /** @return int[] */
    private function resolveOrCreateTagIds(?string $tagsStr, bool $dryRun): array
    {
        if (empty($tagsStr)) {
            return [];
        }
        $names = $this->parseList($tagsStr);
        $ids = [];
        foreach ($names as $name) {
            $name = trim($name);
            if (empty($name)) {
                continue;
            }
            $key = strtolower($name);
            if (isset($this->tagMap[$key])) {
                $ids[] = $this->tagMap[$key];
                continue;
            }
            if (! $dryRun) {
                $tag = BlogTag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                );
                $this->tagMap[$key] = $tag->id;
                $this->tagMap[strtolower($tag->slug)] = $tag->id;
                $ids[] = $tag->id;
            }
        }
        return array_values(array_unique($ids));
    }

    /** @return string[] */
    private function parseList(string $str): array
    {
        $str = str_replace('|', ',', $str);
        $parts = array_map('trim', explode(',', $str));
        return array_filter($parts);
    }
}
