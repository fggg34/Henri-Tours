<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;

class TourPackageController extends Controller
{
    public function index()
    {
        $packages = TourPackage::orderBy('sort_order')->get();

        return view('pages.tour-packages.index', compact('packages'));
    }
}
