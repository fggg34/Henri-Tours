<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HighlightTranslation extends Model
{
    protected $fillable = [
        'highlight_id',
        'locale',
        'title',
        'slug',
        'description',
    ];

    public function highlight(): BelongsTo
    {
        return $this->belongsTo(Highlight::class);
    }
}
