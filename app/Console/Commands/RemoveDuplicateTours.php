<?php

namespace App\Console\Commands;

use App\Models\Tour;
use Illuminate\Console\Command;

class RemoveDuplicateTours extends Command
{
    protected $signature = 'tours:remove-duplicates
                            {--dry-run : Show what would be deleted without actually deleting}';

    protected $description = 'Remove duplicate tours (same title + category), keeping one with most reviews. Handles Spatie slug suffixes (-2, -3).';

    public function handle(): int
    {
        $dupeGroups = Tour::select('title', 'category_id')
            ->groupBy('title', 'category_id')
            ->havingRaw('COUNT(*) > 1')
            ->get(['title', 'category_id']);

        if ($dupeGroups->isEmpty()) {
            $this->info('No duplicate tours found (by title + category).');
            return self::SUCCESS;
        }

        $toDelete = 0;
        foreach ($dupeGroups as $group) {
            $tours = Tour::where('title', $group->title)
                ->where('category_id', $group->category_id)
                ->withCount('reviews')
                ->get();
            $keep = $tours->sortByDesc('reviews_count')->first();
            $remove = $tours->where('id', '!=', $keep->id);
            $toDelete += $remove->count();

            foreach ($remove as $t) {
                if ($this->option('dry-run')) {
                    $this->line("Would delete: id={$t->id} [{$t->title}] slug={$t->slug} ({$t->reviews_count} reviews)");
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
