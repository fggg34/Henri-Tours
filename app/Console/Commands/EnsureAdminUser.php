<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class EnsureAdminUser extends Command
{
    protected $signature = 'tour:ensure-admin {email=admin@albaniainbound.com} {--password=password}';

    protected $description = 'Ensure an admin user exists and can log in to the Filament panel.';

    public function handle(): int
    {
        $email = $this->argument('email');
        $password = $this->option('password');

        $admin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'password' => Hash::make($password),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $admin->update([
            'role' => 'admin',
            'is_active' => true,
            'password' => Hash::make($password),
        ]);

        $this->info("Admin user ready: {$email} / {$password}");
        $this->info('Log in at: ' . url('/admin'));

        return self::SUCCESS;
    }
}
