<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Highlight;

class HighlightController extends Controller
{
    /**
     * For non-localized: (city, highlight). For localized {locale}/cities/{city}/highlights/{highlight}: (locale, city, highlight).
     */
    public function show(string $param1, string $param2, ?string $param3 = null)
    {
        $citySlug = $param3 !== null ? $param2 : $param1;
        $highlightSlug = $param3 !== null ? $param3 : $param2;
        $city = City::where(function ($q) use ($citySlug) {
            $q->where('slug', $citySlug)
                ->orWhereHas('translations', fn ($t) => $t->where('slug', $citySlug));
        })->firstOrFail();
        $highlight = Highlight::where(function ($q) use ($highlightSlug) {
            $q->where('slug', $highlightSlug)
                ->orWhereHas('translations', fn ($t) => $t->where('slug', $highlightSlug));
        })->firstOrFail();

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
