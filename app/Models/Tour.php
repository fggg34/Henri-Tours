<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasTranslations;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tour extends Model
{
    use HasFactory, HasSlug, HasTranslations;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'short_description',
        'price',
        'base_price',
        'currency',
        'duration_hours',
        'duration_days',
        'start_time',
        'start_location',
        'end_location',
        'max_group_size',
        'languages',
        'included',
        'not_included',
        'what_to_bring',
        'important_notes',
        'season',
        'difficulty',
        'tour_highlights',
        'map_lat',
        'map_lng',
        'meta_title',
        'meta_description',
        'is_featured',
        'is_active',
        'sort_order',
        'availability_start_date',
        'availability_end_date',
        'closed_dates',
        'available_weekdays',
        'default_daily_capacity',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'base_price' => 'decimal:2',
            'languages' => 'array',
            'included' => 'array',
            'not_included' => 'array',
            'what_to_bring' => 'array',
            'tour_highlights' => 'array',
            'map_lat' => 'decimal:8',
            'map_lng' => 'decimal:8',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'duration_hours' => 'integer',
            'duration_days' => 'integer',
            'max_group_size' => 'integer',
            'sort_order' => 'integer',
            'availability_start_date' => 'date',
            'availability_end_date' => 'date',
            'closed_dates' => 'array',
            'available_weekdays' => 'array',
            'default_daily_capacity' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Tour $tour) {
            if ($tour->isDirty('base_price') && $tour->base_price !== null) {
                $tour->price = $tour->base_price;
            }
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(TourTranslation::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TourCategory::class, 'category_id');
    }

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'city_tour');
    }

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(TourActivity::class, 'activity_tour');
    }

    public function images(): HasMany
    {
        return $this->hasMany(TourImage::class)->orderBy('sort_order');
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(TourItinerary::class)->orderBy('sort_order')->orderBy('day');
    }

    public function dates(): HasMany
    {
        return $this->hasMany(TourDate::class)->orderBy('date');
    }

    public function pricingTiers(): HasMany
    {
        return $this->hasMany(TourPricingTier::class)->orderBy('min_people');
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(TourDiscount::class)->orderBy('start_date');
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(TourAvailability::class)->orderBy('date');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function wishlistedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    public function getAverageRatingAttribute(): ?float
    {
        return (float) $this->approvedReviews()->avg('rating');
    }

    /**
     * Duplicate this tour as a draft with all fields and related data.
     * Copies: images (same file refs), itineraries, pricing tiers, cities.
     * Does not copy: dates, availabilities, bookings, reviews.
     */
    public function duplicate(): self
    {
        $copy = $this->replicate([
            'slug',
            'created_at',
            'updated_at',
        ]);
        $copy->title = 'Copy of ' . $this->title;
        $copy->slug = null;
        $copy->is_active = false;
        $copy->is_featured = false;
        $copy->save();

        foreach ($this->images as $img) {
            $copy->images()->create([
                'path' => $img->path,
                'alt' => $img->alt,
                'sort_order' => $img->sort_order,
            ]);
        }

        foreach ($this->itineraries as $it) {
            $copy->itineraries()->create([
                'day' => $it->day,
                'title' => $it->title,
                'description' => $it->description,
                'hotel_id' => $it->hotel_id,
                'sort_order' => $it->sort_order,
            ]);
        }

        foreach ($this->pricingTiers as $tier) {
            $copy->pricingTiers()->create([
                'min_people' => $tier->min_people,
                'max_people' => $tier->max_people,
                'price_per_person' => $tier->price_per_person,
            ]);
        }

        foreach ($this->discounts as $discount) {
            $copy->discounts()->create([
                'start_date' => $discount->start_date,
                'end_date' => $discount->end_date,
                'discount_type' => $discount->discount_type,
                'discount_value' => $discount->discount_value,
                'label' => $discount->label,
            ]);
        }

        $copy->cities()->sync($this->cities->pluck('id'));
        $copy->activities()->sync($this->activities->pluck('id'));

        return $copy;
    }
}
