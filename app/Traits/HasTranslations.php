<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasTranslations
{
    /**
     * Get the translation for the given (or current) locale.
     */
    public function getTranslation(?string $locale = null): ?Model
    {
        $locale = $locale ?? app()->getLocale();

        return $this->translations()->where('locale', $locale)->first();
    }

    /**
     * Get the translated value for an attribute.
     * Falls back to the parent model's attribute if no translation exists.
     */
    public function translate(string $attribute, ?string $locale = null)
    {
        $translation = $this->getTranslation($locale);

        if ($translation && $translation->{$attribute} !== null && $translation->{$attribute} !== '') {
            return $translation->{$attribute};
        }

        return $this->getAttribute($attribute);
    }

    /**
     * Get the translated slug. Falls back to parent slug if no translation or empty.
     */
    public function getTranslatedSlug(?string $locale = null): string
    {
        $translation = $this->getTranslation($locale);
        $slug = $translation?->slug;

        return $slug !== null && $slug !== '' ? $slug : (string) $this->slug;
    }
}
