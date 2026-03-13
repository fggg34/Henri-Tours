<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = Cache::rememberForever('setting_' . $key, function () use ($key) {
            return static::where('key', $key)->first();
        });

        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, mixed $value): void
    {
        $raw = is_array($value) || is_object($value) ? json_encode($value) : (string) $value;
        static::updateOrCreate(['key' => $key], ['value' => $raw]);
        Cache::forget('setting_' . $key);
    }

    /**
     * Get a translated value for a setting. Falls back to default Setting::get() when no translation exists.
     */
    public static function getTranslated(string $key, ?string $locale = null, mixed $default = null): mixed
    {
        $locale = $locale ?? app()->getLocale();
        $translation = SettingTranslation::where('setting_key', $key)->where('locale', $locale)->first();

        if ($translation !== null && $translation->value !== null && $translation->value !== '') {
            return $translation->value;
        }

        return static::get($key, $default);
    }

    /**
     * Set a translated value for a setting. Also updates main Setting when locale is 'en' for backward compatibility.
     */
    public static function setTranslated(string $key, mixed $value, string $locale): void
    {
        $raw = is_array($value) || is_object($value) ? json_encode($value) : (string) $value;
        SettingTranslation::updateOrCreate(
            ['setting_key' => $key, 'locale' => $locale],
            ['value' => $raw]
        );

        if ($locale === 'en') {
            static::set($key, $value);
        }

        Cache::forget('setting_' . $key . '_' . $locale);
    }
}
