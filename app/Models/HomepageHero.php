<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageHero extends Model
{
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

    public static function getActive(): ?self
    {
        return static::where('is_active', true)->first();
    }
}
