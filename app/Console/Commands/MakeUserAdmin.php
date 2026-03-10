<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserAdmin extends Command
{
    protected $signature = 'user:make-admin {email : The email of the user to make admin}';

    protected $description = 'Set a user\'s role to admin (fixes 403 on /admin after login)';

    public function handle(): int
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email '{$email}' not found.");
            return self::FAILURE;
        }

        $user->role = 'admin';
        $user->save();

        $this->info("User {$email} is now an admin.");

        return self::SUCCESS;
    }
}
