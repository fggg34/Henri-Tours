<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TourItinerary extends Model
{
    protected $fillable = ['tour_id', 'day', 'title', 'description', 'hotel_id', 'sort_order'];

    protected function casts(): array
    {
        return [
            'day' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function highlights(): BelongsToMany
    {
        return $this->belongsToMany(Highlight::class, 'highlight_tour_itinerary')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }
}
