<?php

namespace App\Http\Controllers;

use App\Models\Hotel;

class HotelController extends Controller
{
    /**
     * For non-localized: (slug). For localized {locale}/hotels/{slug}: (locale, slug).
     */
    public function show(string $param1, ?string $param2 = null)
    {
        $slug = $param2 ?? $param1;
        $hotel = Hotel::where(function ($q) use ($slug) {
            $q->where('slug', $slug)
                ->orWhereHas('translations', fn ($t) => $t->where('slug', $slug));
        })
            ->with(['city.tours', 'city.hotels', 'city.highlights', 'amenities'])
            ->firstOrFail();

        $otherHotels = Hotel::where('city_id', $hotel->city_id)
            ->where('id', '!=', $hotel->id)
            ->take(6)
            ->get();

        return view('pages.hotels.show', compact('hotel', 'otherHotels'));
    }
}
