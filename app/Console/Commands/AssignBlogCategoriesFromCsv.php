<?php

namespace App\Console\Commands;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Console\Command;

class AssignBlogCategoriesFromCsv extends Command
{
    protected $signature = 'blog:assign-categories-from-csv
                            {csv : Path to the Posts export CSV file}
                            {--dry-run : Show what would be updated without writing}';

    protected $description = 'Assign blog categories to existing posts using the CSV Categories column. Run BlogCategorySeeder first.';

    /** @var array<string, int> */
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

        $this->buildCategoryMap();
        if (empty($this->categoryMap)) {
            $this->error('No blog categories found. Run: php artisan db:seed --class=BlogCategorySeeder');
            return self::FAILURE;
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            $this->error('Could not open CSV.');
            return self::FAILURE;
        }

        $header = fgetcsv($handle, 0, ',', '"', '');
        if ($header === false) {
            fclose($handle);
            return self::FAILURE;
        }

        $colMap = [];
        foreach ($header as $i => $col) {
            $col = trim($col, "\xEF\xBB\xBF\"");
            if (! isset($colMap[$col])) {
                $colMap[$col] = $i;
            }
        }

        $updated = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle, 0, ',', '"', '')) !== false) {
            $get = fn (string $name) => trim($row[$colMap[$name] ?? -1] ?? '');

            $title = $get('Title');
            if (empty($title)) {
                continue;
            }

            if (strtolower($get('Post Type')) !== 'post') {
                continue;
            }

            $slug = $get('Slug');
            if (empty($slug)) {
                $slug = \Illuminate\Support\Str::slug($title);
            }

            $categoryId = $this->resolveCategoryId($get('Categories'));
            if ($categoryId === null) {
                $skipped++;
                continue;
            }

            $post = BlogPost::where('slug', $slug)->first();
            if (! $post) {
                $skipped++;
                continue;
            }

            if ($post->blog_category_id == $categoryId) {
                continue;
            }

            if (! $dryRun) {
                $post->update(['blog_category_id' => $categoryId]);
            }
            $this->line("  {$post->title} → " . BlogCategory::find($categoryId)->name);
            $updated++;
        }

        fclose($handle);

        $this->newLine();
        $this->info(($dryRun ? 'Would update' : 'Updated') . ": {$updated} | Skipped: {$skipped}");

        return self::SUCCESS;
    }

    private function buildCategoryMap(): void
    {
        foreach (BlogCategory::all() as $cat) {
            $this->categoryMap[strtolower($cat->name)] = $cat->id;
            $this->categoryMap[strtolower($cat->slug)] = $cat->id;
        }
    }

    private function resolveCategoryId(?string $categoriesStr): ?int
    {
        if (empty($categoriesStr)) {
            return BlogCategory::first()?->id;
        }
        $first = trim(explode('|', $categoriesStr)[0]);
        if (empty($first)) {
            return BlogCategory::first()?->id;
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
