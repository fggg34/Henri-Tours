<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourDiscount extends Model
{
    protected $fillable = [
        'tour_id',
        'start_date',
        'end_date',
        'discount_type',
        'discount_value',
        'label',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'discount_value' => 'decimal:2',
        ];
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function appliesOn(\Carbon\Carbon $date): bool
    {
        $d = $date->copy()->startOfDay();
        return $d->between($this->start_date->copy()->startOfDay(), $this->end_date->copy()->endOfDay());
    }

    public function apply(float $pricePerPerson): float
    {
        if ($this->discount_type === 'percent') {
            $percent = min(100, max(0, (float) $this->discount_value));
            return $pricePerPerson * (1 - $percent / 100);
        }
        // fixed: subtract from price per person (cannot go below 0)
        $fixed = (float) $this->discount_value;
        return max(0, $pricePerPerson - $fixed);
    }
}
