<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class HomepageHero extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title',
        'subtitle',
        'banner_type',
        'banner_image',
        'banner_video',
        'cta_text',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getBannerImageUrlAttribute(): ?string
    {
        if (empty($this->banner_image)) {
            return null;
        }
        return '/storage/' . ltrim($this->banner_image, '/');
    }

    public function getBannerVideoUrlAttribute(): ?string
    {
        if (empty($this->banner_video)) {
            return null;
        }
        return '/storage/' . ltrim($this->banner_video, '/');
    }

    public function translations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(HomepageHeroTranslation::class);
    }

    public static function getActive(): ?self
    {
        return static::where('is_active', true)->first();
    }
}
