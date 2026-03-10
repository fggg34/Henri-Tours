<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Travel Tips', 'slug' => 'travel-tips'],
            ['name' => 'Destinations', 'slug' => 'destinations'],
            ['name' => 'News', 'slug' => 'news'],
        ];

        foreach ($categories as $cat) {
            BlogCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
