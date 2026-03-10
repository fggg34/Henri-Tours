<?php

namespace App\Console\Commands;

use App\Models\Tour;
use App\Models\TourCategory;
use Illuminate\Console\Command;

class ExportToursForProduction extends Command
{
    protected $signature = 'tours:export-for-production';

    protected $description = 'Export all tours, categories, images and itineraries to JSON for one-time import on production';

    public function handle(): int
    {
        $path = database_path('seeders/data/tours-export.json');

        $categories = TourCategory::orderBy('id')->get()->map(fn ($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'slug' => $c->slug,
            'description' => $c->description,
            'image' => $c->image,
            'sort_order' => $c->sort_order,
        ])->toArray();

        $tours = Tour::with(['images', 'itineraries', 'reviews'])->orderBy('id')->get();
        $toursData = [];
        $imagesData = [];
        $itinerariesData = [];
        $reviewsData = [];

        foreach ($tours as $tour) {
            $toursData[] = $tour->only([
                'id', 'category_id', 'title', 'slug', 'description', 'short_description',
                'price', 'base_price', 'currency', 'duration_hours', 'duration_days',
                'start_time', 'start_location', 'end_location', 'max_group_size', 'languages',
                'included', 'not_included', 'what_to_bring', 'important_notes', 'season',
                'difficulty', 'tour_highlights', 'map_lat', 'map_lng', 'meta_title', 'meta_description',
                'is_featured', 'is_active', 'sort_order', 'availability_start_date', 'availability_end_date',
                'closed_dates', 'available_weekdays', 'default_daily_capacity',
            ]);
            foreach ($tour->images as $img) {
                $imagesData[] = [
                    'tour_id' => $tour->id,
                    'path' => $img->path,
                    'alt' => $img->alt,
                    'sort_order' => $img->sort_order,
                ];
            }
            foreach ($tour->itineraries as $itr) {
                $itinerariesData[] = [
                    'tour_id' => $tour->id,
                    'day' => $itr->day,
                    'title' => $itr->title,
                    'description' => $itr->description,
                    'sort_order' => $itr->sort_order,
                ];
            }
            foreach ($tour->reviews as $rev) {
                $reviewsData[] = [
                    'tour_id' => $tour->id,
                    'name' => $rev->name,
                    'review_date' => $rev->review_date?->format('Y-m-d'),
                    'rating' => $rev->rating,
                    'title' => $rev->title,
                    'comment' => $rev->comment,
                    'is_approved' => $rev->is_approved,
                    'platform' => $rev->platform,
                    'platform_tour_url' => $rev->platform_tour_url,
                ];
            }
        }

        $export = [
            'exported_at' => now()->toIso8601String(),
            'categories' => $categories,
            'tours' => $toursData,
            'images' => $imagesData,
            'itineraries' => $itinerariesData,
            'reviews' => $reviewsData,
        ];

        file_put_contents($path, json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        $this->info('Exported ' . count($toursData) . ' tours, ' . count($reviewsData) . ' reviews to ' . $path);
        $this->info('Commit this file and run on production: php artisan db:seed --class=ToursFromExportSeeder');

        return self::SUCCESS;
    }
}
