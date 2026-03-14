<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelTranslation extends Model
{
    protected $fillable = [
        'hotel_id',
        'locale',
        'name',
        'slug',
        'description',
        'location',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
}
