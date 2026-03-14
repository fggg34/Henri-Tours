<?php

namespace App\Console\Commands;

use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\TourImage;
use App\Models\TourItinerary;
use App\Models\TourItineraryTranslation;
use App\Models\TourTranslation;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportToursFromCsv extends Command
{
    protected $signature = 'tours:import-csv
                            {file : Path to the CSV file}
                            {--only-published : Only import rows with Status=publish}
                            {--dry-run : Show what would be imported without writing}
                            {--fresh : Delete all existing tours first, then import}';

    protected $description = 'Import tours from WordPress/WPML CSV export (WP All Export Pro). Groups by WPML Translation ID and creates Tour + TourTranslation per language.';

    private array $colMap = [];

    /** Booking Categories (CSV) -> Laravel TourCategory slug */
    private const BOOKING_CATEGORY_TO_SLUG = [
        'Day Tour' => 'day-tours',
        'Multi-Day Tour' => 'multi-day-tours',
        'Cross Country' => 'cross-country-tours',
    ];

    public function handle(): int
    {
        $path = $this->argument('file');
        if (! is_file($path) || ! is_readable($path)) {
            $this->error("File not found or not readable: {$path}");

            return self::FAILURE;
        }

        $onlyPublished = (bool) $this->option('only-published');
        $dryRun = (bool) $this->option('dry-run');
        $fresh = (bool) $this->option('fresh');

        if ($dryRun) {
            $this->warn('DRY RUN - no data will be written.');
        }

        if ($fresh && ! $dryRun) {
            $count = Tour::query()->count();
            Tour::query()->delete();
            $this->info("Removed {$count} existing tours (and related records).");
        }

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

        $rows = [];
        $rowNum = 1;
        while (($row = fgetcsv($handle, 0, ',', '"', '')) !== false) {
            $rowNum++;
            $wpmlId = $this->getCol($row, 'WPML Translation ID');
            if ($wpmlId === '') {
                continue;
            }
            if ($onlyPublished) {
                $status = $this->getCol($row, 'Status');
                if (strtolower(trim($status)) !== 'publish') {
                    continue;
                }
            }
            $title = $this->getCol($row, 'Title');
            if ($title === '' || $title === 'Title') {
                continue;
            }
            $rows[$wpmlId][] = $row;
        }
        fclose($handle);

        $groupCount = count($rows);
        $this->info("Grouped {$groupCount} tour groups by WPML Translation ID.");

        if ($dryRun) {
            $sample = array_slice($rows, 0, 5, true);
            foreach ($sample as $wpmlId => $group) {
                $base = $this->pickBaseRow($group);
                $title = $this->getCol($base, 'Title');
                $slug = $this->getCol($base, 'Slug') ?: Str::slug($title);
                $locales = array_unique(array_map(fn ($r) => $this->getCol($r, 'WPML Language Code'), $group));
                $this->line("  Would import: {$title} ({$slug}) [" . implode(', ', $locales) . ']');
            }
            $this->newLine();
            $this->info("Dry run: would process {$groupCount} tour groups.");

            return self::SUCCESS;
        }

        $imported = 0;
        $skipped = 0;
        $bar = $this->output->createProgressBar($groupCount);
        $bar->start();

        foreach ($rows as $wpmlId => $group) {
            $base = $this->pickBaseRow($group);
            $slug = $this->getCol($base, 'Slug');
            $title = $this->getCol($base, 'Title');
            if ($slug === '') {
                $slug = Str::slug($title);
            }

            if (! $fresh && Tour::where('slug', $slug)->exists()) {
                $skipped++;
                $bar->advance();
                continue;
            }

            try {
                $category = $this->resolveCategory($base);
                $tour = $this->createBaseTour($base, $category, $slug);
                $this->importImages($tour, $base);
                $this->importItineraries($tour, $base);

                foreach ($group as $row) {
                    $locale = $this->getCol($row, 'WPML Language Code');
                    if ($locale === '') {
                        continue;
                    }
                    $isBase = ($row === $base);
                    if ($isBase) {
                        $this->createTranslation($tour, $row, $locale, true);
                        $this->importItineraryTranslations($tour, $row, $locale);
                    } else {
                        $this->createTranslation($tour, $row, $locale, false);
                        $this->importItineraryTranslations($tour, $row, $locale);
                    }
                }
                $imported++;
            } catch (\Throwable $e) {
                $this->newLine();
                $this->error("Error importing group {$wpmlId}: {$e->getMessage()}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
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

    /** Pick English row as base, or first row. */
    private function pickBaseRow(array $group): array
    {
        foreach ($group as $row) {
            if ($this->getCol($row, 'WPML Language Code') === 'en') {
                return $row;
            }
        }

        return $group[0];
    }

    private function resolveCategory(array $baseRow): TourCategory
    {
        $bookingCategory = $this->getCol($baseRow, 'Booking Categories');
        $slug = self::BOOKING_CATEGORY_TO_SLUG[$bookingCategory] ?? Str::slug($bookingCategory);
        if ($slug === '') {
            $slug = 'day-tours';
        }

        return TourCategory::firstOrCreate(
            ['slug' => $slug],
            ['name' => $bookingCategory ?: 'Tours', 'sort_order' => 0]
        );
    }

    private function createBaseTour(array $row, TourCategory $category, string $slug): Tour
    {
        $title = $this->getCol($row, 'Title');
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
        $whatToExpect = $this->getCol($row, 'what_to_expect');

        $durationHours = $this->parseDuration($durationRaw);
        $maxGroupSize = $this->parseInt($groupSize) ?: $this->parseInt($guests);
        $languages = array_filter(array_map('trim', explode(',', $languagesRaw)));
        $included = $this->parseJsonArray($includedRaw);
        $excluded = $this->parseJsonArray($excludedRaw);

        return Tour::create([
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
    }

    private function createTranslation(Tour $tour, array $row, string $locale, bool $isBaseRow): void
    {
        $title = $this->getCol($row, 'Title');
        $slug = $this->getCol($row, 'Slug') ?: Str::slug($title);
        $content = $this->getCol($row, 'Content');
        $excerpt = $this->getCol($row, 'Excerpt');
        $location = $this->getCol($row, 'Location');
        $startTime = $this->getCol($row, 'Card Icons_starting_time');
        $languagesRaw = $this->getCol($row, 'Card Icons_live_tour_guide');
        $includedRaw = $this->getCol($row, 'Day tour Included_included');
        $excludedRaw = $this->getCol($row, 'Day tour Included_excluded');
        $whatToExpect = $this->getCol($row, 'what_to_expect');

        $languages = array_filter(array_map('trim', explode(',', $languagesRaw)));
        $included = $this->parseJsonArray($includedRaw);
        $excluded = $this->parseJsonArray($excludedRaw);

        TourTranslation::updateOrCreate(
            ['tour_id' => $tour->id, 'locale' => $locale],
            [
                'category_id' => $tour->category_id,
                'title' => $title,
                'slug' => $slug,
                'description' => $content ?: null,
                'short_description' => $excerpt ?: null,
                'start_location' => $location ?: null,
                'end_location' => null,
                'start_time' => $this->normalizeTime($startTime) ?: null,
                'languages' => $languages ?: null,
                'included' => $included ?: null,
                'not_included' => $excluded ?: null,
                'what_to_bring' => $this->parseWhatToBring($whatToExpect),
                'important_notes' => null,
                'tour_highlights' => null,
                'meta_title' => null,
                'meta_description' => null,
            ]
        );
    }

    private function importImages(Tour $tour, array $baseRow): void
    {
        $imagesRaw = $this->getCol($baseRow, 'images');
        $featuredRaw = $this->getCol($baseRow, 'tmp_featured_image');
        $urls = [];
        if ($featuredRaw !== '' && filter_var($featuredRaw, FILTER_VALIDATE_URL)) {
            $urls[] = $featuredRaw;
        }
        if ($imagesRaw !== '') {
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

    private function importItineraries(Tour $tour, array $baseRow): void
    {
        $stepsRaw = $this->getCol($baseRow, 'steps_day-tours');
        if ($stepsRaw === '') {
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

    private function importItineraryTranslations(Tour $tour, array $row, string $locale): void
    {
        $stepsRaw = $this->getCol($row, 'steps_day-tours');
        $steps = $this->parsePhpSerializedSteps($stepsRaw);
        $itineraries = $tour->itineraries()->orderBy('sort_order')->get();
        foreach ($steps as $i => $step) {
            $itinerary = $itineraries->get($i);
            if (! $itinerary) {
                break;
            }
            $title = $step['title'] ?? $itinerary->title;
            $description = $step['attraction'] ?? $itinerary->description;
            TourItineraryTranslation::updateOrCreate(
                ['tour_itinerary_id' => $itinerary->id, 'locale' => $locale],
                [
                    'title' => Str::limit($title, 255),
                    'description' => $description ?: null,
                ]
            );
        }
    }

    private function parseDate(?string $val): ?string
    {
        if ($val === null || trim($val) === '') {
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
            return (int) $m[1] * 24;
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

            return $items ?: null;
        }

        return null;
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
                foreach ($m as $match) {
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
