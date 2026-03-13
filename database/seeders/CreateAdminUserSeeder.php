<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Creates the admin user for Filament panel access.
     * Safe to run on production. Use firstOrCreate to avoid duplicates.
     *
     * Usage: php artisan db:seed --class=CreateAdminUserSeeder
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@albaniainbound.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $admin->update([
            'role' => 'admin',
            'is_active' => true,
            'password' => Hash::make('password'),
        ]);

        $this->command?->info('Admin user created/updated. Email: admin@albaniainbound.com');
    }
}
