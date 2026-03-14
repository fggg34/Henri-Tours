<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class LocaleController extends Controller
{
    protected array $supportedLocales = ['en', 'zh_CN', 'fr', 'de', 'he', 'it', 'mt', 'es'];

    public function switch(string $locale): RedirectResponse
    {
        if (! in_array($locale, $this->supportedLocales, true)) {
            return Redirect::back();
        }

        // Use redirect param (more reliable) or referer to get the page we came from
        $currentPath = request()->path();
        $path = '/';
        if (preg_match('#^lang/' . preg_quote($locale, '#') . '$#', $currentPath)) {
            $redirect = request()->query('redirect');
            if ($redirect && filter_var($redirect, FILTER_VALIDATE_URL)) {
                $host = parse_url($redirect, PHP_URL_HOST);
                if ($host && $host === request()->getHost()) {
                    $path = '/' . ltrim((string) parse_url($redirect, PHP_URL_PATH), '/');
                }
            }
            if ($path === '/' && ($referer = request()->header('Referer'))) {
                $path = '/' . ltrim((string) parse_url($referer, PHP_URL_PATH), '/');
            }
        } else {
            $path = '/' . ltrim($currentPath, '/');
        }

        // Strip application base path (e.g. /public) to avoid /public/public/... when app runs in subdirectory
        $basePath = rtrim((string) parse_url(config('app.url'), PHP_URL_PATH), '/');
        if ($basePath !== '' && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath)) ?: '/';
        }
        // Fallback: strip /public when APP_URL has no path (common on shared hosting)
        if (str_starts_with($path, '/public/') || $path === '/public') {
            $path = preg_replace('#^/public#', '', $path) ?: '/';
        }

        // Strip any existing locale prefix (e.g. /fr/tours → /tours, /public/fr → /public)
        foreach ($this->supportedLocales as $l) {
            if ($l === 'en') {
                continue;
            }
            $prefix = '/' . $l . '/';
            if (str_starts_with($path, $prefix)) {
                $path = '/' . substr($path, strlen($prefix));
                break;
            }
            if ($path === '/' . $l || rtrim($path, '/') === '/' . $l) {
                $path = '/';
                break;
            }
            // Handle locale as last segment (e.g. /public/fr)
            if (str_ends_with(rtrim($path, '/'), '/' . $l)) {
                $path = substr($path, 0, -strlen($l) - 1) ?: '/';
                break;
            }
        }

        $path = trim($path, '/') ?: '';

        // Add locale prefix for non-English; English has no prefix
        if ($locale !== 'en') {
            $path = $locale . ($path ? '/' . $path : '');
        }

        $targetPath = '/' . $path;
        $query = request()->query('redirect') ? parse_url(request()->query('redirect'), PHP_URL_QUERY) : null;
        if ($query) {
            $targetPath .= '?' . $query;
        }

        // Use url() so Laravel builds the correct absolute URL (avoids /public/public when in subdirectory)
        return Redirect::to(URL::to($targetPath));
    }
}
