<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Highlight;

class HighlightController extends Controller
{
    public function show(string $city, string $highlight, ?string $locale = null)
    {
        $city = City::where('slug', $city)->firstOrFail();
        $highlight = Highlight::where('slug', $highlight)->firstOrFail();

        if (! $city->highlights->contains($highlight)) {
            abort(404);
        }

        $otherHighlights = $city->highlights()
            ->where('highlights.id', '!=', $highlight->id)
            ->orderByPivot('sort_order')
            ->get();

        return view('pages.highlights.show', [
            'city' => $city,
            'highlight' => $highlight,
            'otherHighlights' => $otherHighlights,
        ]);
    }
}
