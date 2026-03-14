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

    /**
     * For non-localized: (slug). For localized {locale}/cities/{slug}: (locale, slug).
     */
    public function show(string $param1, ?string $param2 = null)
    {
        $slug = $param2 ?? $param1;
        $city = City::where(function ($q) use ($slug) {
            $q->where('slug', $slug)
                ->orWhereHas('translations', fn ($t) => $t->where('slug', $slug));
        })
            ->with(['hotels', 'highlights', 'tours' => fn ($q) => $q->where('is_active', true)])
            ->firstOrFail();

        return view('pages.cities.show', compact('city'));
    }
}
