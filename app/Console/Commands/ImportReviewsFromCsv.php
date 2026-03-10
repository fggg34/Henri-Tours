<?php

namespace App\Console\Commands;

use App\Models\Review;
use App\Models\Tour;
use Illuminate\Console\Command;

class ImportReviewsFromCsv extends Command
{
    protected $signature = 'reviews:import-csv
                            {reviews : Path to the site-reviews CSV file}
                            {tours : Path to the Tours export CSV (for ID->slug mapping)}
                            {--dry-run : Show what would be imported without writing}';

    protected $description = 'Import reviews from CSV, matching assigned_posts (WP ID) to tours via Tours export CSV ID column.';

    /** @var array<int, string> wp_id => slug */
    private array $wpIdToSlug = [];

    public function handle(): int
    {
        $reviewsPath = $this->argument('reviews');
        $toursPath = $this->argument('tours');

        if (! is_file($reviewsPath) || ! is_readable($reviewsPath)) {
            $this->error("Reviews file not found or not readable: {$reviewsPath}");
            return self::FAILURE;
        }
        if (! is_file($toursPath) || ! is_readable($toursPath)) {
            $this->error("Tours file not found or not readable: {$toursPath}");
            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        if ($dryRun) {
            $this->warn('DRY RUN - no data will be written.');
        }

        $this->buildWpIdToSlugMap($toursPath);
        $this->info('Loaded ' . count($this->wpIdToSlug) . ' tour ID->slug mappings.');

        $handle = fopen($reviewsPath, 'r');
        if ($handle === false) {
            $this->error('Could not open reviews file.');
            return self::FAILURE;
        }

        $header = fgetcsv($handle, 0, ',', '"', '');
        if ($header === false) {
            fclose($handle);
            $this->error('Empty or invalid reviews CSV.');
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

            $assignedPosts = $get('assigned_posts');
            if (empty($assignedPosts)) {
                $skipped++;
                continue;
            }
            $wpId = (int) preg_replace('/[^0-9].*$/', '', $assignedPosts);
            if ($wpId <= 0) {
                $skipped++;
                continue;
            }

            $slug = $this->wpIdToSlug[$wpId] ?? null;
            if (! $slug) {
                $skipped++;
                continue;
            }

            $tour = Tour::where('slug', $slug)->first();
            if (! $tour) {
                $this->line("  Skip (tour not found for slug {$slug}): wp_id={$wpId}");
                $skipped++;
                continue;
            }

            $rating = (int) $get('rating');
            if ($rating < 1 || $rating > 5) {
                $rating = 5;
            }
            $title = $get('title');
            $content = $get('content');
            $name = $get('name');
            $dateStr = $get('date');
            $isApproved = in_array(strtolower(trim($get('is_approved'))), ['1', 'true', 'yes'], true);
            $platform = $get('custom_platform') ?: null;
            $platformUrl = $get('custom_tour_url') ?: null;

            $reviewDate = null;
            if (! empty($dateStr)) {
                try {
                    $dt = \DateTime::createFromFormat('Y-m-d H:i:s', trim($dateStr));
                    if ($dt) {
                        $reviewDate = $dt->format('Y-m-d');
                    }
                } catch (\Throwable) {
                    // ignore
                }
            }

            if ($dryRun) {
                $this->line("  Would import: \"{$title}\" -> {$tour->title} (rating {$rating})");
                $imported++;
                continue;
            }

            try {
                Review::create([
                    'tour_id' => $tour->id,
                    'name' => $name ?: null,
                    'review_date' => $reviewDate,
                    'rating' => $rating,
                    'title' => $title ?: null,
                    'comment' => $content ?: null,
                    'is_approved' => $isApproved,
                    'platform' => $platform,
                    'platform_tour_url' => $platformUrl,
                ]);
                $this->line("  Imported: \"{$title}\" -> {$tour->title}");
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

    private function buildWpIdToSlugMap(string $toursPath): void
    {
        $handle = fopen($toursPath, 'r');
        if ($handle === false) {
            return;
        }
        $header = fgetcsv($handle, 0, ',', '"', '');
        if ($header === false) {
            fclose($handle);
            return;
        }
        $colMap = [];
        foreach ($header as $i => $col) {
            $col = trim($col, "\xEF\xBB\xBF\"");
            if (! isset($colMap[$col])) {
                $colMap[$col] = $i;
            }
        }

        while (($row = fgetcsv($handle, 0, ',', '"', '')) !== false) {
            $idVal = trim($row[$colMap['ID'] ?? -1] ?? '');
            if ($idVal === '' || ! ctype_digit($idVal)) {
                continue;
            }
            $wpId = (int) $idVal;
            $permalink = trim($row[$colMap['Permalink'] ?? -1] ?? '');
            $slug = trim($row[$colMap['Slug'] ?? -1] ?? '');

            if (empty($slug) && ! empty($permalink)) {
                if (preg_match('#/tour/([^/?]+)#', $permalink, $m)) {
                    $slug = $m[1];
                }
            }
            if (! empty($slug)) {
                $this->wpIdToSlug[$wpId] = $slug;
            }
        }
        fclose($handle);
    }
}
