<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\TourImage;
use App\Models\TourItinerary;
use Illuminate\Database\Seeder;

class ToursFromExportSeeder extends Seeder
{
    /**
     * One-time import of tours exported from local. Run on production after deploy.
     * Usage: php artisan db:seed --class=ToursFromExportSeeder
     */
    public function run(): void
    {
        $path = database_path('seeders/data/tours-export.json');

        if (! file_exists($path)) {
            $this->command?->error('Export file not found. Run locally: php artisan tours:export-for-production');
            return;
        }

        $data = json_decode(file_get_contents($path), true);
        if (! $data || ! isset($data['categories'], $data['tours'])) {
            $this->command?->error('Invalid export file.');
            return;
        }

        $catMap = [];
        foreach ($data['categories'] as $c) {
            $existing = TourCategory::where('slug', $c['slug'])->first();
            if ($existing) {
                $catMap[$c['id']] = $existing->id;
                continue;
            }
            $new = TourCategory::create([
                'name' => $c['name'],
                'slug' => $c['slug'],
                'description' => $c['description'] ?? null,
                'image' => $c['image'] ?? null,
                'sort_order' => $c['sort_order'] ?? 0,
            ]);
            $catMap[$c['id']] = $new->id;
        }

        $tourMap = [];
        foreach ($data['tours'] as $t) {
            $categoryId = $catMap[$t['category_id']] ?? null;
            if (! $categoryId) {
                continue;
            }
            $attrs = [
                'category_id' => $categoryId,
                'title' => $t['title'],
                'description' => $t['description'] ?? null,
                'short_description' => $t['short_description'] ?? null,
                'price' => $t['price'] ?? 0,
                'base_price' => $t['base_price'] ?? null,
                'currency' => $t['currency'] ?? 'USD',
                'duration_hours' => $t['duration_hours'] ?? null,
                'duration_days' => $t['duration_days'] ?? null,
                'start_time' => $t['start_time'] ?? null,
                'start_location' => $t['start_location'] ?? null,
                'end_location' => $t['end_location'] ?? null,
                'max_group_size' => $t['max_group_size'] ?? null,
                'languages' => $t['languages'] ?? null,
                'included' => $t['included'] ?? null,
                'not_included' => $t['not_included'] ?? null,
                'what_to_bring' => $t['what_to_bring'] ?? null,
                'important_notes' => $t['important_notes'] ?? null,
                'season' => $t['season'] ?? null,
                'difficulty' => $t['difficulty'] ?? null,
                'tour_highlights' => $t['tour_highlights'] ?? null,
                'map_lat' => $t['map_lat'] ?? null,
                'map_lng' => $t['map_lng'] ?? null,
                'meta_title' => $t['meta_title'] ?? null,
                'meta_description' => $t['meta_description'] ?? null,
                'is_featured' => (bool) ($t['is_featured'] ?? false),
                'is_active' => (bool) ($t['is_active'] ?? true),
                'sort_order' => $t['sort_order'] ?? 0,
                'availability_start_date' => $t['availability_start_date'] ?? null,
                'availability_end_date' => $t['availability_end_date'] ?? null,
                'closed_dates' => $t['closed_dates'] ?? null,
                'available_weekdays' => $t['available_weekdays'] ?? null,
                'default_daily_capacity' => $t['default_daily_capacity'] ?? null,
            ];
            $tour = Tour::firstOrCreate(['slug' => $t['slug']], array_merge($attrs, ['slug' => $t['slug']]));
            $tourMap[$t['id']] = $tour->id;
        }

        $images = $data['images'] ?? [];
        foreach ($images as $img) {
            $tourId = $tourMap[$img['tour_id']] ?? null;
            if ($tourId) {
                TourImage::firstOrCreate(
                    ['tour_id' => $tourId, 'path' => $img['path'] ?? ''],
                    ['alt' => $img['alt'] ?? null, 'sort_order' => $img['sort_order'] ?? 0]
                );
            }
        }

        $itineraries = $data['itineraries'] ?? [];
        foreach ($itineraries as $itr) {
            $tourId = $tourMap[$itr['tour_id']] ?? null;
            if ($tourId) {
                TourItinerary::firstOrCreate(
                    [
                        'tour_id' => $tourId,
                        'day' => $itr['day'] ?? null,
                        'title' => $itr['title'] ?? '',
                    ],
                    ['description' => $itr['description'] ?? null, 'sort_order' => $itr['sort_order'] ?? 0]
                );
            }
        }

        $reviews = $data['reviews'] ?? [];
        $reviewsImported = 0;
        foreach ($reviews as $rev) {
            $tourId = $tourMap[$rev['tour_id']] ?? null;
            if ($tourId) {
                $created = Review::firstOrCreate(
                    [
                        'tour_id' => $tourId,
                        'name' => $rev['name'] ?? null,
                        'comment' => $rev['comment'] ?? null,
                        'review_date' => $rev['review_date'] ?? null,
                    ],
                    [
                        'rating' => (int) ($rev['rating'] ?? 5),
                        'title' => $rev['title'] ?? null,
                        'is_approved' => (bool) ($rev['is_approved'] ?? true),
                        'platform' => $rev['platform'] ?? null,
                        'platform_tour_url' => $rev['platform_tour_url'] ?? null,
                    ]
                );
                if ($created->wasRecentlyCreated) {
                    $reviewsImported++;
                }
            }
        }

        $this->command?->info('Imported ' . count($tourMap) . ' tours, ' . $reviewsImported . ' reviews successfully.');
    }
}
