<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

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

        // Strip any existing locale prefix (e.g. /fr/tours → /tours)
        foreach ($this->supportedLocales as $l) {
            if ($l === 'en') {
                continue;
            }
            $prefix = '/' . $l . '/';
            if (str_starts_with($path, $prefix)) {
                $path = '/' . substr($path, strlen($prefix));
                break;
            }
            if ($path === '/' . $l) {
                $path = '/';
                break;
            }
        }

        $path = trim($path, '/') ?: '';

        // Add locale prefix for non-English; English has no prefix
        if ($locale !== 'en') {
            $path = $locale . ($path ? '/' . $path : '');
        }

        $url = '/' . $path;
        $query = request()->query('redirect') ? parse_url(request()->query('redirect'), PHP_URL_QUERY) : null;
        if ($query) {
            $url .= '?' . $query;
        }

        return Redirect::to($url);
    }
}
