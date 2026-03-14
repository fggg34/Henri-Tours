<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Highlight extends Model
{
    use HasFactory, HasSlug, HasTranslations;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'sort_order',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(HighlightTranslation::class);
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }
        return '/storage/' . ltrim($this->image, '/');
    }

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'city_highlight')
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    public function tourItineraries(): BelongsToMany
    {
        return $this->belongsToMany(TourItinerary::class, 'highlight_tour_itinerary')
            ->withPivot('sort_order')
            ->withTimestamps();
    }
}
