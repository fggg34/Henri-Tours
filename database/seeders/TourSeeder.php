<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\TourDate;
use App\Models\TourImage;
use App\Models\TourItinerary;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    public function run(): void
    {
        $dayTours = TourCategory::where('slug', 'day-tours')->first();
        $multiDay = TourCategory::where('slug', 'multi-day-tours')->first();
        if (! $dayTours || ! $multiDay) {
            return;
        }

        $tours = [
            [
                'category_id' => $dayTours->id,
                'title' => 'Coastal Discovery Day Tour',
                'short_description' => 'Explore stunning coastlines and hidden beaches in one day.',
                'description' => '<p>Join us for a full-day coastal adventure. Visit pristine beaches, enjoy local cuisine, and discover scenic viewpoints. Perfect for nature lovers and photography enthusiasts.</p>',
                'price' => 89.00,
                'currency' => 'EUR',
                'duration_hours' => 8,
                'duration_days' => null,
                'start_time' => '09:00',
                'start_location' => 'City Center',
                'end_location' => 'City Center',
                'max_group_size' => 15,
                'languages' => ['English', 'German'],
                'included' => ['Transport', 'Guide', 'Lunch'],
                'not_included' => ['Personal expenses', 'Tips'],
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'category_id' => $dayTours->id,
                'title' => 'Mountain & Village Experience',
                'short_description' => 'Discover mountain landscapes and traditional villages.',
                'description' => '<p>A day in the mountains with visits to traditional villages, local crafts, and breathtaking views. Includes a traditional lunch.</p>',
                'price' => 75.00,
                'currency' => 'EUR',
                'duration_hours' => 10,
                'duration_days' => null,
                'start_time' => '08:00',
                'start_location' => 'Main Square',
                'end_location' => 'Main Square',
                'max_group_size' => 12,
                'languages' => ['English'],
                'included' => ['Transport', 'Guide', 'Lunch'],
                'not_included' => ['Personal expenses'],
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'category_id' => $multiDay->id,
                'title' => '5-Day North to South Explorer',
                'short_description' => 'Five days exploring the best of the region from north to south.',
                'description' => '<p>An immersive five-day journey from northern mountains to southern coast. Includes accommodation, most meals, and all activities. Small group for a personal experience.</p>',
                'price' => 650.00,
                'currency' => 'EUR',
                'duration_hours' => null,
                'duration_days' => 5,
                'start_time' => '09:00',
                'start_location' => 'Capital City',
                'end_location' => 'Coastal City',
                'max_group_size' => 8,
                'languages' => ['English', 'Italian'],
                'included' => ['Accommodation', 'Breakfast & dinner', 'Transport', 'Guide', 'Entrance fees'],
                'not_included' => ['Lunches', 'Personal expenses'],
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($tours as $index => $data) {
            $tour = Tour::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($data['title'])],
                array_merge($data, ['meta_title' => $data['title'], 'meta_description' => $data['short_description']])
            );

            if ($tour->itineraries()->count() === 0) {
                $days = $tour->duration_days ?? 1;
                for ($d = 1; $d <= $days; $d++) {
                    TourItinerary::create([
                        'tour_id' => $tour->id,
                        'day' => $d,
                        'title' => "Day {$d} highlights",
                        'description' => "Description for day {$d}.",
                        'sort_order' => $d,
                    ]);
                }
            }

            if ($tour->dates()->count() === 0) {
                for ($i = 0; $i < 5; $i++) {
                    TourDate::create([
                        'tour_id' => $tour->id,
                        'date' => now()->addDays(7 + $i * 3),
                        'price' => $tour->price,
                        'available_slots' => $tour->max_group_size ?? 10,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
