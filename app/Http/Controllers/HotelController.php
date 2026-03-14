<?php

namespace App\Http\Controllers;

use App\Models\Hotel;

class HotelController extends Controller
{
    public function show(string $slug, ?string $locale = null)
    {
        $hotel = Hotel::where('slug', $slug)
            ->with(['city.tours', 'city.hotels', 'city.highlights', 'amenities'])
            ->firstOrFail();

        $otherHotels = Hotel::where('city_id', $hotel->city_id)
            ->where('id', '!=', $hotel->id)
            ->take(6)
            ->get();

        return view('pages.hotels.show', compact('hotel', 'otherHotels'));
    }
}
