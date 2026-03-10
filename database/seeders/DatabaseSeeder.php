<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TourCategorySeeder::class,
            SettingSeeder::class,
            AmenitySeeder::class,
            HomepageHeroSeeder::class,
        ]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@coral-travel.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );
        // Always ensure admin has correct role and can log in (in case user existed before)
        $admin->update([
            'role' => 'admin',
            'is_active' => true,
            'password' => Hash::make('password'),
        ]);

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ]
        );

        $this->call([
            TourSeeder::class,
            BlogCategorySeeder::class,
            BlogPostSeeder::class,
        ]);
    }
}
