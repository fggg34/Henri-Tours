<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingTranslation extends Model
{
    protected $fillable = ['setting_key', 'locale', 'value'];

    public static function getValue(string $key, string $locale): mixed
    {
        $record = static::where('setting_key', $key)->where('locale', $locale)->first();

        return $record !== null ? $record->value : null;
    }

    public static function setValue(string $key, string $locale, mixed $value): void
    {
        $raw = is_array($value) || is_object($value) ? json_encode($value) : (string) $value;
        static::updateOrCreate(
            ['setting_key' => $key, 'locale' => $locale],
            ['value' => $raw]
        );
    }
}
