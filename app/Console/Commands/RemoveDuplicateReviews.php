<?php

namespace App\Console\Commands;

use App\Models\Review;
use Illuminate\Console\Command;

class RemoveDuplicateReviews extends Command
{
    protected $signature = 'reviews:remove-duplicates
                            {--dry-run : Show what would be deleted without deleting}';

    protected $description = 'Remove duplicate reviews (same tour_id + title + comment + name + review_date).';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        if ($dryRun) {
            $this->warn('DRY RUN - no reviews will be deleted.');
        }

        $reviews = Review::orderBy('id')->get();
        $groups = [];
        foreach ($reviews as $r) {
            $key = implode('|', [
                $r->tour_id,
                $r->title ?? '',
                substr($r->comment ?? '', 0, 500),
                $r->name ?? '',
                $r->review_date?->format('Y-m-d') ?? '',
            ]);
            $groups[$key][] = $r;
        }

        $deleted = 0;
        foreach ($groups as $key => $group) {
            if (count($group) <= 1) {
                continue;
            }
            $keep = $group[0];
            foreach (array_slice($group, 1) as $dup) {
                if ($dryRun) {
                    $this->line("  Would delete duplicate: #{$dup->id} \"{$dup->title}\" (tour_id={$dup->tour_id})");
                } else {
                    $dup->delete();
                    $this->line("  Deleted duplicate: #{$dup->id} \"{$dup->title}\" (tour_id={$dup->tour_id})");
                }
                $deleted++;
            }
        }

        $this->newLine();
        $this->info(($dryRun ? 'Would delete' : 'Deleted') . " {$deleted} duplicate review(s).");

        return self::SUCCESS;
    }
}
