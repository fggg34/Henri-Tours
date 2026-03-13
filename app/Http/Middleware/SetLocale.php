<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    protected array $supportedLocales = ['en', 'zh_CN', 'fr', 'de', 'he', 'it', 'mt', 'es'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('locale');

        if ($locale && in_array($locale, $this->supportedLocales, true)) {
            App::setLocale($locale);
        } else {
            App::setLocale(config('app.locale', 'en'));
        }

        return $next($request);
    }
}
