<?php

namespace App\Filament\Traits;

use App\Models\Setting;
use Filament\Forms\Components\Select;

trait HasTranslatablePageContent
{
    protected static array $supportedLocales = ['en', 'zh_CN', 'fr', 'de', 'he', 'it', 'mt', 'es'];

    protected function getCurrentLocale(): string
    {
        return request()->query('locale', session('admin_page_locale', 'en'));
    }

    protected function setCurrentLocale(string $locale): void
    {
        session(['admin_page_locale' => $locale]);
    }

    protected function getTranslatedSetting(string $key, mixed $default = null): mixed
    {
        return Setting::getTranslated($key, $this->getCurrentLocale(), $default);
    }

    protected function setTranslatedSetting(string $key, mixed $value): void
    {
        Setting::setTranslated($key, $value, $this->getCurrentLocale());
    }

    protected function getLocaleSelectSchema(): Select
    {
        return Select::make('_locale')
            ->label('Content language')
            ->options(array_combine(
                static::$supportedLocales,
                array_map(fn ($l) => __("locales.{$l}"), static::$supportedLocales)
            ))
            ->default($this->getCurrentLocale())
            ->live()
            ->afterStateUpdated(function (string $state): void {
                $this->setCurrentLocale($state);
                // Use static::getUrl() - request()->url() would be the Livewire update endpoint (POST-only) during the callback
                $this->redirect(static::getUrl(['locale' => $state]));
            })
            ->dehydrated(false)
            ->columnSpanFull();
    }
}
