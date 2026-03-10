<?php

namespace App\Console\Commands;

use App\Models\Review;
use Illuminate\Console\Command;

class DeleteAllReviews extends Command
{
    protected $signature = 'reviews:delete-all
                            {--force : Skip confirmation}';

    protected $description = 'Delete all reviews. Run ToursFromExportSeeder afterwards to re-import.';

    public function handle(): int
    {
        $count = Review::count();

        if ($count === 0) {
            $this->info('No reviews to delete.');
            return self::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm("Delete all {$count} review(s)?")) {
            $this->info('Cancelled.');
            return self::SUCCESS;
        }

        Review::query()->delete();
        $this->info("Deleted {$count} review(s).");

        $this->line('');
        $this->info('To re-import reviews, run: php artisan db:seed --class=ToursFromExportSeeder');

        return self::SUCCESS;
    }
}
