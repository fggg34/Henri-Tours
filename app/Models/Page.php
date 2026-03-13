<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = [
        'slug',
        'template',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(PageTranslation::class)->orderBy('locale');
    }

    /**
     * Get translated attribute for the current app locale, falling back to default (en) or first available.
     */
    public function translated(string $attribute, ?string $locale = null): mixed
    {
        $locale = $locale ?? app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();

        if ($translation && $translation->{$attribute} !== null && $translation->{$attribute} !== '') {
            return $translation->{$attribute};
        }

        // Fallback to default locale (en)
        if ($locale !== 'en') {
            $default = $this->translations()->where('locale', 'en')->first();
            if ($default && $default->{$attribute} !== null && $default->{$attribute} !== '') {
                return $default->{$attribute};
            }
        }

        return null;
    }
}
