<?php

namespace Database\Seeders;

use App\Models\TourCategory;
use Illuminate\Database\Seeder;

class TourCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Day Tours', 'slug' => 'day-tours', 'description' => 'Single day excursions', 'sort_order' => 1],
            ['name' => 'Multi-Day Tours', 'slug' => 'multi-day-tours', 'description' => 'Multi-day packages', 'sort_order' => 2],
            ['name' => 'Cross-country Tours', 'slug' => 'cross-country-tours', 'description' => 'Cross-country adventures', 'sort_order' => 3],
            ['name' => 'Private Tours', 'slug' => 'private-tours', 'description' => 'Private guided tours', 'sort_order' => 4],
        ];

        foreach ($categories as $cat) {
            TourCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
