<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class BlogTag extends Model
{
    use HasFactory, HasSlug, HasTranslations;

    protected $fillable = ['name', 'slug'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function translations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BlogTagTranslation::class);
    }

    public function scopeWhereSlugOrTranslation($query, string $slug)
    {
        return $query->where('slug', $slug)
            ->orWhereHas('translations', fn ($q) => $q->where('slug', $slug));
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();
        if ($field === 'slug') {
            return $query->where('slug', $value)
                ->orWhereHas('translations', fn ($q) => $q->where('slug', $value));
        }
        return $query->where($field, $value);
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(BlogPost::class, 'blog_post_tag');
    }
}
