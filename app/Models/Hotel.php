<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Hotel extends Model
{
    use HasFactory, HasSlug, HasTranslations;

    protected $fillable = [
        'city_id',
        'name',
        'slug',
        'image',
        'gallery',
        'stars_rating',
        'total_reviews',
        'description',
        'location',
        'map_lat',
        'map_lng',
        'house_rules',
        'phone',
        'email',
        'website',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(HotelTranslation::class);
    }

    protected function casts(): array
    {
        return [
            'stars_rating' => 'integer',
            'total_reviews' => 'integer',
            'house_rules' => 'array',  // [ ['label' => 'Check In', 'value' => '12:00 pm'], ... ]
            'gallery' => 'array',   // [ 'path1', 'path2', ... ] additional images
        ];
    }

    /** All display images: main image first, then gallery. Returns array of full URLs. */
    public function getAllImageUrls(): array
    {
        $urls = [];
        if ($this->image) {
            $urls[] = '/storage/' . ltrim($this->image, '/');
        }
        foreach ($this->gallery ?? [] as $path) {
            if (is_string($path) && $path !== '') {
                $urls[] = str_starts_with($path, 'http') ? $path : '/storage/' . ltrim($path, '/');
            }
        }
        return $urls;
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'amenity_hotel');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }
        return '/storage/' . ltrim($this->image, '/');
    }

    /** Google Maps URL for this hotel (opens in new tab). */
    public function getGoogleMapsUrlAttribute(): ?string
    {
        if ($this->map_lat && $this->map_lng) {
            return 'https://www.google.com/maps?q=' . urlencode((string) $this->map_lat . ',' . (string) $this->map_lng);
        }
        if ($this->location) {
            return 'https://www.google.com/maps/search/?api=1&query=' . urlencode($this->location);
        }
        return null;
    }
}
