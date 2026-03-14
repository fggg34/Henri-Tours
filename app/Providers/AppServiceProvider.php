<?php

namespace App\Providers;

use App\Contracts\PaymentServiceInterface;
use App\Services\NullPaymentService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentServiceInterface::class, NullPaymentService::class);

        // Ensure helpers (localized_route, localized_url) are always loaded
        // Even if Composer autoload files weren't refreshed on deploy
        require_once app_path('helpers.php');
    }

    public function boot(): void
    {
        // Ensure tourForLanguageSwitch is always set for the language switcher (avoids errors on non-tour pages)
        view()->share('tourForLanguageSwitch', null);

        // Prevent /public from appearing in URLs when site is served from document root.
        // Use FORCE_APP_URL in .env to override, or we strip /public from APP_URL.
        $forceUrl = env('FORCE_APP_URL');
        if ($forceUrl) {
            \Illuminate\Support\Facades\URL::forceRootUrl(rtrim($forceUrl, '/'));
        } else {
            $appUrl = config('app.url');
            if (str_ends_with(rtrim($appUrl, '/'), '/public')) {
                \Illuminate\Support\Facades\URL::forceRootUrl(preg_replace('#/public/?$#', '', $appUrl) ?: $appUrl);
            }
        }
    }
}
