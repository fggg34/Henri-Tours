<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomepageHeroTranslation extends Model
{
    protected $fillable = [
        'homepage_hero_id',
        'locale',
        'title',
        'subtitle',
    ];

    public function homepageHero(): BelongsTo
    {
        return $this->belongsTo(HomepageHero::class);
    }
}
