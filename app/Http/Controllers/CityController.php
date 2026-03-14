<?php

namespace App\Http\Controllers;

use App\Models\City;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::query()
            ->withCount(['hotels', 'tours'])
            ->orderBy('name')
            ->get();

        return view('pages.cities.index', compact('cities'));
    }

    public function show(string $slug, ?string $locale = null)
    {
        $city = City::where('slug', $slug)
            ->with(['hotels', 'highlights', 'tours' => fn ($q) => $q->where('is_active', true)])
            ->firstOrFail();

        return view('pages.cities.show', compact('city'));
    }
}
