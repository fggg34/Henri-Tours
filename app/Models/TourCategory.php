<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class TourCategory extends Model
{
    use HasFactory, HasSlug, HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'sort_order',
        'hero_title',
        'hero_subtitle',
        'hero_image',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function translations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TourCategoryTranslation::class);
    }

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class, 'category_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }
        return '/storage/' . ltrim($this->image, '/');
    }

    public function getHeroImageUrlAttribute(): ?string
    {
        if (empty($this->hero_image)) {
            return null;
        }
        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->hero_image);
    }
}
