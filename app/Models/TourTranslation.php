<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourTranslation extends Model
{
    protected $fillable = [
        'tour_id',
        'locale',
        'category_id',
        'title',
        'slug',
        'description',
        'short_description',
        'start_location',
        'end_location',
        'start_time',
        'languages',
        'included',
        'not_included',
        'what_to_bring',
        'important_notes',
        'tour_highlights',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'included' => 'array',
            'not_included' => 'array',
            'what_to_bring' => 'array',
            'tour_highlights' => 'array',
            'languages' => 'array',
        ];
    }

    protected $appends = ['itinerary_items'];

    public function getItineraryItemsAttribute(): array
    {
        $tour = $this->tour;
        if (! $tour) {
            return [];
        }

        return $tour->itineraries->map(function ($it) {
            $tr = $it->translations()->where('locale', $this->locale)->first();

            return [
                'tour_itinerary_id' => $it->id,
                'day' => $it->day,
                'title' => $tr?->title ?? $it->title,
                'description' => $tr?->description ?? $it->description,
            ];
        })->values()->toArray();
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TourCategory::class, 'category_id');
    }

    public function activities(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(TourActivity::class, 'activity_tour_translation');
    }
}
