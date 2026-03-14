<?php

if (! function_exists('localized_route')) {
    /**
     * Generate a frontend URL for the given route.
     * English (default) = no prefix: /tours
     * Others = locale-prefixed route name: /fr/tours, /de/tours, etc.
     */
    function localized_route(string $name, array $parameters = [], bool $absolute = true, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        if ($locale !== 'en') {
            $parameters = ['locale' => $locale] + $parameters;

            return route(str_starts_with($name, 'localized.') ? $name : 'localized.' . $name, $parameters, $absolute);
        }

        return route($name, $parameters, $absolute);
    }
}

if (! function_exists('localized_url')) {
    /**
     * Prefix a raw frontend path with locale when needed.
     */
    function localized_url(string $path = '/', ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        $normalizedPath = '/' . ltrim($path, '/');

        if ($locale === 'en') {
            return url($normalizedPath);
        }

        foreach (['zh_CN', 'fr', 'de', 'he', 'it', 'mt', 'es'] as $supportedLocale) {
            if ($normalizedPath === '/' . $supportedLocale || str_starts_with($normalizedPath, '/' . $supportedLocale . '/')) {
                return url($normalizedPath);
            }
        }

        return url('/' . $locale . ($normalizedPath === '/' ? '' : $normalizedPath));
    }
}
