<?php

namespace App\Console\Commands;

use App\Models\Tour;
use Illuminate\Console\Command;

class DeleteAllTours extends Command
{
    protected $signature = 'tours:delete-all
                            {--force : Skip confirmation}';

    protected $description = 'Delete all tours (and their reviews, images, itineraries via cascade).';

    public function handle(): int
    {
        $count = Tour::count();

        if ($count === 0) {
            $this->info('No tours to delete.');
            return self::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm("Delete all {$count} tour(s) and related data?")) {
            $this->info('Cancelled.');
            return self::SUCCESS;
        }

        Tour::query()->delete();
        $this->info("Deleted {$count} tour(s).");

        return self::SUCCESS;
    }
}
