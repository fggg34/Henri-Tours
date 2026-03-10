<?php

namespace App\Console\Commands;

use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\TourImage;
use App\Models\TourItinerary;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportToursFromCsv extends Command
{
    protected $signature = 'tours:import-csv
                            {file : Path to the CSV file}
                            {--category= : Tour category name (default: Tours)}
                            {--only-published : Only import rows with Status=publish}
                            {--dry-run : Show what would be imported without writing}
                            {--fresh : Delete existing tours first (same category slug), then import}';

    protected $description = 'Import tours from WordPress/Albania Inbound CSV export.';

    private array $colMap = [];

    public function handle(): int
    {
        $path = $this->argument('file');
        if (! is_file($path) || ! is_readable($path)) {
            $this->error("File not found or not readable: {$path}");

            return self::FAILURE;
        }

        $categoryName = $this->option('category') ?: 'Tours';
        $onlyPublished = (bool) $this->option('only-published');
        $dryRun = (bool) $this->option('dry-run');
        $fresh = (bool) $this->option('fresh');

        if ($dryRun) {
            $this->warn('DRY RUN - no data will be written.');
        }

        if ($fresh && ! $dryRun) {
            $deleted = Tour::query()->delete();
            $this->info("Removed {$deleted} existing tours.");
        }

        $category = TourCategory::firstOrCreate(
            ['slug' => Str::slug($categoryName)],
            ['name' => $categoryName, 'sort_order' => 0]
        );

        $handle = fopen($path, 'r');
        if ($handle === false) {
            $this->error('Could not open file.');

            return self::FAILURE;
        }

        $header = fgetcsv($handle, 0, ',', '"', '');
        if ($header === false) {
            fclose($handle);
            $this->error('Empty or invalid CSV.');

            return self::FAILURE;
        }

        $this->colMap = [];
        foreach ($header as $i => $col) {
            $col = trim($col, "\xEF\xBB\xBF\"");
            if (! isset($this->colMap[$col])) {
                $this->colMap[$col] = $i;
            }
        }

        $imported = 0;
        $skipped = 0;
        $rowNum = 1;

        while (($row = fgetcsv($handle, 0, ',', '"', '')) !== false) {
            $rowNum++;

            $title = $this->getCol($row, 'Title');
            if (empty($title) || $title === 'Title') {
                $skipped++;

                continue;
            }

            if ($onlyPublished) {
                $status = $this->getCol($row, 'Status');
                if (strtolower(trim($status)) !== 'publish') {
                    $skipped++;

                    continue;
                }
            }

            $slug = $this->getCol($row, 'Slug');
            if (empty($slug)) {
                $slug = Str::slug($title);
            }

            if (Tour::where('slug', $slug)->exists() && ! $dryRun) {
                $this->line("  Skip (slug exists): {$title}");
                $skipped++;

                continue;
            }

            if ($dryRun) {
                $this->line("  Would import: {$title} ({$slug})");
                $imported++;

                continue;
            }

            try {
                $this->importRow($row, $category, $slug, $title);
                $this->line("  Imported: {$title}");
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

    private function getCol(array $row, string $name): string
    {
        $idx = $this->colMap[$name] ?? null;
        if ($idx === null) {
            return '';
        }

        return trim($row[$idx] ?? '');
    }

    private function importRow(array $row, TourCategory $category, string $slug, string $title): void
    {
        $content = $this->getCol($row, 'Content');
        $excerpt = $this->getCol($row, 'Excerpt');
        $location = $this->getCol($row, 'Location');
        $startTime = $this->getCol($row, 'Card Icons_starting_time');
        $groupSize = $this->getCol($row, 'Card Icons_group_size');
        $difficulty = $this->getCol($row, 'Card Icons_difficulty');
        $languagesRaw = $this->getCol($row, 'Card Icons_live_tour_guide');
        $includedRaw = $this->getCol($row, 'Day tour Included_included');
        $excludedRaw = $this->getCol($row, 'Day tour Included_excluded');
        $startDate = $this->parseDate($this->getCol($row, 'start_date'));
        $endDate = $this->parseDate($this->getCol($row, 'end_date'));
        $guests = $this->getCol($row, 'guests');
        $minGuests = $this->getCol($row, 'min_guests');
        $durationRaw = $this->getCol($row, 'duration_day-tours');
        $stepsRaw = $this->getCol($row, 'steps_day-tours');
        $imagesRaw = $this->getCol($row, 'images');
        $featuredRaw = $this->getCol($row, 'tmp_featured_image');
        $whatToExpect = $this->getCol($row, 'what_to_expect');

        $durationHours = $this->parseDuration($durationRaw);
        $maxGroupSize = $this->parseInt($groupSize) ?: $this->parseInt($guests);

        $languages = array_filter(array_map('trim', explode(',', $languagesRaw)));
        $included = $this->parseJsonArray($includedRaw);
        $excluded = $this->parseJsonArray($excludedRaw);

        $tour = Tour::create([
            'category_id' => $category->id,
            'title' => $title,
            'slug' => $slug,
            'description' => $content ?: null,
            'short_description' => $excerpt ?: null,
            'base_price' => null,
            'currency' => 'EUR',
            'duration_hours' => $durationHours,
            'duration_days' => $durationHours ? (int) ceil($durationHours / 24) : null,
            'start_time' => $this->normalizeTime($startTime) ?: null,
            'start_location' => $location ?: null,
            'end_location' => null,
            'max_group_size' => $maxGroupSize,
            'languages' => $languages ?: null,
            'included' => $included ?: null,
            'not_included' => $excluded ?: null,
            'what_to_bring' => $this->parseWhatToBring($whatToExpect),
            'important_notes' => null,
            'difficulty' => $difficulty ?: null,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 0,
            'availability_start_date' => $startDate,
            'availability_end_date' => $endDate,
        ]);

        $this->importImages($tour, $imagesRaw, $featuredRaw);
        $this->importItineraries($tour, $stepsRaw);
    }

    private function parseDate(?string $val): ?string
    {
        if (empty($val)) {
            return null;
        }
        $val = trim($val);
        try {
            $d = \DateTime::createFromFormat('d/m/Y', $val);
            if ($d) {
                return $d->format('Y-m-d');
            }
            $d = \DateTime::createFromFormat('Y-m-d', $val);
            if ($d) {
                return $d->format('Y-m-d');
            }
        } catch (\Throwable) {
        }

        return null;
    }

    private function parseInt(?string $val): ?int
    {
        if ($val === null || $val === '') {
            return null;
        }
        $n = (int) preg_replace('/[^0-9]/', '', $val);

        return $n > 0 ? $n : null;
    }

    private function parseDuration(?string $val): ?int
    {
        if (empty($val)) {
            return null;
        }
        if (preg_match('/"h";s:\d+:"(\d+)"/', $val, $m)) {
            return (int) $m[1];
        }
        if (preg_match('/"d";s:\d+:"(\d+)"/', $val, $m)) {
            $days = (int) $m[1];

            return $days * 24;
        }

        return null;
    }

    private function normalizeTime(?string $val): ?string
    {
        if (empty($val)) {
            return null;
        }
        $val = trim($val);
        if (preg_match('/^(\d{1,2}):(\d{2})/', $val, $m)) {
            return sprintf('%02d:%02d', (int) $m[1], (int) $m[2]);
        }

        return $val;
    }

    private function parseJsonArray(?string $val): ?array
    {
        if (empty($val)) {
            return null;
        }
        $decoded = json_decode($val, true);
        if (! is_array($decoded)) {
            return null;
        }
        $items = [];
        foreach ($decoded as $item) {
            if (isset($item['text']) && $item['text']) {
                $items[] = $item['text'];
            }
        }

        return $items ?: null;
    }

    private function parseWhatToBring(?string $val): ?array
    {
        if (empty($val)) {
            return null;
        }
        if (str_contains($val, 'a:') && preg_match_all('/s:\d+:"([^"]+)"/', $val, $m)) {
            $items = array_values(array_unique(array_filter($m[1])));
            if (! empty($items)) {
                return $items;
            }
        }

        return null;
    }

    private function importImages(Tour $tour, ?string $imagesRaw, ?string $featuredRaw): void
    {
        $urls = [];
        if (! empty($featuredRaw) && filter_var($featuredRaw, FILTER_VALIDATE_URL)) {
            $urls[] = $featuredRaw;
        }
        if (! empty($imagesRaw)) {
            $decoded = @unserialize($imagesRaw);
            if (is_array($decoded)) {
                foreach ($decoded as $item) {
                    if (is_array($item) && isset($item['image']) && filter_var($item['image'], FILTER_VALIDATE_URL)) {
                        $url = $item['image'];
                        if (! in_array($url, $urls)) {
                            $urls[] = $url;
                        }
                    }
                }
            }
            if (empty($urls) || count($urls) === 1) {
                $parts = array_filter(array_map('trim', explode('|', $imagesRaw)));
                foreach ($parts as $url) {
                    if (filter_var($url, FILTER_VALIDATE_URL) && ! in_array($url, $urls)) {
                        $urls[] = $url;
                    }
                }
            }
        }
        foreach ($urls as $i => $url) {
            TourImage::create([
                'tour_id' => $tour->id,
                'path' => $url,
                'alt' => $tour->title . ' - ' . ($i + 1),
                'sort_order' => $i,
            ]);
        }
    }

    private function importItineraries(Tour $tour, ?string $stepsRaw): void
    {
        if (empty($stepsRaw)) {
            return;
        }
        $steps = $this->parsePhpSerializedSteps($stepsRaw);
        foreach ($steps as $i => $step) {
            $title = $step['title'] ?? ('Step ' . ($i + 1));
            $description = $step['attraction'] ?? '';
            TourItinerary::create([
                'tour_id' => $tour->id,
                'day' => 1,
                'title' => Str::limit($title, 255),
                'description' => $description ?: null,
                'sort_order' => $i,
            ]);
        }
    }

    private function parsePhpSerializedSteps(?string $val): array
    {
        if (empty($val) || ! str_contains($val, 'a:')) {
            return [];
        }
        $decoded = @unserialize($val);
        if (! is_array($decoded)) {
            if (preg_match_all('/s:5:"title";s:\d+:"([^"]*)".*?s:10:"attraction";s:\d+:"([^"]*)"/s', $val, $m, PREG_SET_ORDER)) {
                $out = [];
                foreach ($m as $i => $match) {
                    $out[] = ['title' => $match[1], 'attraction' => $match[2]];
                }

                return $out;
            }

            return [];
        }
        $out = [];
        foreach ($decoded as $item) {
            if (is_array($item) && isset($item['title'])) {
                $out[] = [
                    'title' => $item['title'],
                    'attraction' => $item['attraction'] ?? '',
                ];
            }
        }

        return $out;
    }
}
