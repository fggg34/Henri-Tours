<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourActivityTranslation extends Model
{
    protected $fillable = [
        'tour_activity_id',
        'locale',
        'title',
    ];

    public function tourActivity(): BelongsTo
    {
        return $this->belongsTo(TourActivity::class);
    }
}
