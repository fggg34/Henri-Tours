<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourCategoryTranslation extends Model
{
    protected $fillable = [
        'tour_category_id',
        'locale',
        'name',
        'slug',
        'description',
        'hero_title',
        'hero_subtitle',
    ];

    public function tourCategory(): BelongsTo
    {
        return $this->belongsTo(TourCategory::class);
    }
}
