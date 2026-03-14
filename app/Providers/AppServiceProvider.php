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
    }
}
