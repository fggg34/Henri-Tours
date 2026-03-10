<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class AboutController extends Controller
{
    public function __invoke()
    {
        return view('pages.about');
    }
}
