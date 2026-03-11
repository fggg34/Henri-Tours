<?php

namespace App\Console\Commands;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Console\Command;

class ImportBlogPostsFromCsv extends Command
{
    protected $signature = 'blog:import-csv
                            {csv : Path to the Posts export CSV file}
                            {--dry-run : Show what would be imported without writing}';

    protected $description = 'Import blog posts from Albania Inbound WordPress CSV. Keeps existing categories, maps CSV categories to them.';

    /** @var array<string, int> category name (normalized) => blog_category_id */
    private array $categoryMap = [];

    public function handle(): int
    {
        $path = $this->argument('csv');

        if (! is_file($path) || ! is_readable($path)) {
            $this->error("File not found or not readable: {$path}");
            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        if ($dryRun) {
            $this->warn('DRY RUN - no data will be written.');
        }

        $user = User::first();
        if (! $user) {
            $this->error('No user found. Run CreateAdminUserSeeder first.');
            return self::FAILURE;
        }

        $this->buildCategoryMap();

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
            $get = fn (string $name) => trim($row[$colMap[$name] ?? -1] ?? '');

            $title = $get('Title');
            if (empty($title)) {
                $skipped++;
                continue;
            }

            $postType = $get('Post Type');
            if (strtolower($postType) !== 'post') {
                $skipped++;
                continue;
            }

            $status = $get('Status');
            $isPublished = in_array(strtolower($status), ['publish', 'published'], true);

            $slug = $get('Slug');
            if (empty($slug)) {
                $slug = \Illuminate\Support\Str::slug($title);
            }

            $content = $get('Content');
            $excerpt = $get('Excerpt');
            $dateStr = $get('Date');
            $featuredUrl = $get('URL');
            if (empty($featuredUrl) && isset($colMap['Featured'])) {
                $featuredUrl = $get('Featured');
            }
            $categoriesStr = $get('Categories');

            $publishedAt = null;
            if (! empty($dateStr)) {
                try {
                    $dt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dateStr);
                    if ($dt) {
                        $publishedAt = $isPublished ? $dt : null;
                    }
                } catch (\Throwable) {
                    // ignore
                }
            }
            if ($isPublished && ! $publishedAt) {
                $publishedAt = now();
            }

            $categoryId = $this->resolveCategoryId($categoriesStr);

            if ($dryRun) {
                $this->line("  Would import: [{$slug}] {$title}");
                $imported++;
                continue;
            }

            try {
                BlogPost::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'blog_category_id' => $categoryId,
                        'user_id' => $user->id,
                        'title' => $title,
                        'excerpt' => $excerpt ?: null,
                        'content' => $content ?: null,
                        'featured_image' => $featuredUrl ?: null,
                        'meta_title' => $title,
                        'meta_description' => $excerpt ? \Illuminate\Support\Str::limit(strip_tags($excerpt), 160) : null,
                        'is_published' => $isPublished,
                        'published_at' => $publishedAt,
                    ]
                );
                $this->line("  Imported: [{$slug}]");
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

    private function buildCategoryMap(): void
    {
        $categories = BlogCategory::all();
        foreach ($categories as $cat) {
            $this->categoryMap[strtolower($cat->name)] = $cat->id;
            $this->categoryMap[strtolower($cat->slug)] = $cat->id;
        }
        $this->info('Existing categories: ' . $categories->pluck('name')->join(', '));
    }

    private function resolveCategoryId(?string $categoriesStr): ?int
    {
        if (empty($categoriesStr)) {
            return null;
        }
        $first = trim(explode('|', $categoriesStr)[0]);
        if (empty($first)) {
            return null;
        }
        $key = strtolower($first);
        if (isset($this->categoryMap[$key])) {
            return $this->categoryMap[$key];
        }
        $map = [
            'day trips and adventures' => 'destinations',
            'good to know' => 'travel-tips',
            'seasonal highlights' => 'destinations',
            'things to do' => 'destinations',
            'photography hotspots' => 'destinations',
        ];
        $slug = $map[$key] ?? null;
        if ($slug) {
            $cat = BlogCategory::where('slug', $slug)->first();
            return $cat?->id;
        }
        return BlogCategory::first()?->id;
    }
}
