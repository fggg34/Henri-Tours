<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    protected array $supportedLocales = ['en', 'zh_CN', 'fr', 'de', 'he', 'it', 'mt', 'es'];

    public function switch(string $locale): RedirectResponse
    {
        if (! in_array($locale, $this->supportedLocales, true)) {
            return Redirect::back();
        }

        Session::put('locale', $locale);

        return Redirect::back();
    }
}
