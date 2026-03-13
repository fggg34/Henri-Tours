<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourItineraryTranslation extends Model
{
    protected $fillable = [
        'tour_itinerary_id',
        'locale',
        'title',
        'description',
    ];

    public function tourItinerary(): BelongsTo
    {
        return $this->belongsTo(TourItinerary::class);
    }
}
