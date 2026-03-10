<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourAvailability extends Model
{
    protected $fillable = [
        'tour_id',
        'date',
        'capacity',
        'booked_spots',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'capacity' => 'integer',
            'booked_spots' => 'integer',
        ];
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function getAvailableSpotsAttribute(): int
    {
        return max(0, $this->capacity - $this->booked_spots);
    }

    public function isFullyBooked(): bool
    {
        return $this->booked_spots >= $this->capacity;
    }
}
