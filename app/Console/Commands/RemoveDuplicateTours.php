<?php

namespace App\Console\Commands;

use App\Models\Tour;
use Illuminate\Console\Command;

class RemoveDuplicateTours extends Command
{
    protected $signature = 'tours:remove-duplicates
                            {--dry-run : Show what would be deleted without actually deleting}';

    protected $description = 'Remove duplicate tours, keeping one per slug (prefers the one with most reviews). Run after accidental duplicate import.';

    public function handle(): int
    {
        $duplicates = Tour::select('slug')
            ->groupBy('slug')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('slug');

        if ($duplicates->isEmpty()) {
            $this->info('No duplicate tours found.');
            return self::SUCCESS;
        }

        $toDelete = 0;
        foreach ($duplicates as $slug) {
            $tours = Tour::where('slug', $slug)->withCount('reviews')->get();
            $keep = $tours->sortByDesc('reviews_count')->first();
            $remove = $tours->where('id', '!=', $keep->id);
            $toDelete += $remove->count();

            foreach ($remove as $t) {
                if ($this->option('dry-run')) {
                    $this->line("Would delete: id={$t->id} [{$t->title}] (slug: {$slug}, {$t->reviews_count} reviews)");
                } else {
                    $t->delete();
                    $this->line("Deleted: id={$t->id} [{$t->title}]");
                }
            }
        }

        if ($this->option('dry-run')) {
            $this->warn("Would delete {$toDelete} duplicate tour(s). Run without --dry-run to apply.");
        } else {
            $this->info("Removed {$toDelete} duplicate tour(s).");
        }

        return self::SUCCESS;
    }
}
