<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            ['name' => 'Free Wi-Fi', 'icon' => 'fa-solid fa-wifi', 'sort_order' => 1],
            ['name' => 'Pool', 'icon' => 'fa-solid fa-person-swimming', 'sort_order' => 2],
            ['name' => 'Spa', 'icon' => 'fa-solid fa-spa', 'sort_order' => 3],
            ['name' => 'Parking', 'icon' => 'fa-solid fa-square-parking', 'sort_order' => 4],
            ['name' => 'Air Conditioning', 'icon' => 'fa-solid fa-snowflake', 'sort_order' => 5],
            ['name' => 'Restaurant', 'icon' => 'fa-solid fa-utensils', 'sort_order' => 6],
            ['name' => 'Bar', 'icon' => 'fa-solid fa-martini-glass', 'sort_order' => 7],
            ['name' => 'Fitness Center', 'icon' => 'fa-solid fa-dumbbell', 'sort_order' => 8],
            ['name' => 'Room Service', 'icon' => 'fa-solid fa-bell-concierge', 'sort_order' => 9],
            ['name' => 'Airport Shuttle', 'icon' => 'fa-solid fa-van-shuttle', 'sort_order' => 10],
        ];

        foreach ($amenities as $amenity) {
            Amenity::firstOrCreate(
                ['name' => $amenity['name']],
                [
                    'icon' => $amenity['icon'],
                    'sort_order' => $amenity['sort_order'],
                ]
            );
        }
    }
}
