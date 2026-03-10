<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourPricingTier extends Model
{
    protected $fillable = [
        'tour_id',
        'min_people',
        'max_people',
        'price_per_person',
    ];

    protected function casts(): array
    {
        return [
            'min_people' => 'integer',
            'max_people' => 'integer',
            'price_per_person' => 'decimal:2',
        ];
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function matches(int $people): bool
    {
        if ($people < $this->min_people) {
            return false;
        }
        if ($this->max_people === null) {
            return true;
        }
        return $people <= $this->max_people;
    }
}
