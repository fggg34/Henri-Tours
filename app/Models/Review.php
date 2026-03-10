<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = ['tour_id', 'user_id', 'name', 'review_date', 'rating', 'title', 'comment', 'is_approved', 'platform', 'platform_tour_url'];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_approved' => 'boolean',
            'review_date' => 'date',
        ];
    }

    /** Display name: manual name, or user name, or Anonymous */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: $this->user?->name ?: 'Anonymous';
    }

    /** Date to show on frontend: review_date or created_at */
    public function getDisplayDateAttribute()
    {
        return $this->review_date ?? $this->created_at;
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
