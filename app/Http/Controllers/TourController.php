<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Tour;
use App\Models\TourCategory;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $query = Tour::where('is_active', true)->with(['category', 'cities', 'images', 'approvedReviews']);

        if ($request->filled('city')) {
            $query->whereHas('cities', fn ($q) => $q->where('slug', $request->city));
        }
        if ($request->filled('adults')) {
            $adults = (int) $request->adults;
            if ($adults >= 1) {
                $query->where(function ($q) use ($adults) {
                    $q->whereNull('max_group_size')->orWhere('max_group_size', '>=', $adults);
                });
            }
        }
        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }
        if ($request->filled('duration')) {
            $durations = is_array($request->duration) ? $request->duration : [$request->duration];
            $query->where(function ($q) use ($durations) {
                foreach ($durations as $d) {
                    $hours = (int) $d;
                    if ($hours > 0) {
                        $q->orWhere('duration_hours', $hours)->orWhere('duration_days', $hours);
                    }
                }
            });
        }
        if ($request->boolean('on_sale')) {
            $query->whereNotNull('base_price')->whereColumn('price', '<', 'base_price');
        }
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(fn ($qry) => $qry->where('title', 'like', "%{$search}%")->orWhere('short_description', 'like', "%{$search}%"));
        }
        if ($request->has('season')) {
            $seasons = array_values(array_filter((array) $request->input('season', [])));
            if (!empty($seasons)) {
                $query->whereIn('season', $seasons);
            }
        }

        $sort = $request->get('sort', 'popular');
        match ($sort) {
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            'newest' => $query->orderByDesc('created_at'),
            default => $query->withCount('bookings')->orderByDesc('is_featured')->orderByDesc('bookings_count'),
        };

        $tours = $query->paginate(12)->withQueryString();
        $categories = TourCategory::orderBy('sort_order')->get();
        $cities = City::active()->orderBy('name')->get();
        $wishlistedIds = auth()->user()?->wishlistTours()->pluck('tours.id')->toArray() ?? [];

        $durationOptions = Tour::where('is_active', true)
            ->select('duration_hours', 'duration_days')
            ->get()
            ->map(function ($t) {
                if ($t->duration_days) {
                    return ['value' => $t->duration_days, 'label' => $t->duration_days . ' ' . ($t->duration_days === 1 ? 'day' : 'days'), 'sort' => $t->duration_days * 24];
                }
                if ($t->duration_hours) {
                    return ['value' => $t->duration_hours, 'label' => $t->duration_hours . ' ' . ($t->duration_hours === 1 ? 'hour' : 'hours'), 'sort' => $t->duration_hours];
                }
                return null;
            })
            ->filter()
            ->unique('label')
            ->sortBy('sort')
            ->values();

        $priceRange = [
            'min' => (int) Tour::where('is_active', true)->min('price'),
            'max' => (int) Tour::where('is_active', true)->max('price'),
        ];

        $seasonOptions = collect([
            ['value' => 'summer', 'label' => 'Summer'],
            ['value' => 'winter', 'label' => 'Winter'],
            ['value' => 'all_season', 'label' => 'All Season'],
        ]);

        return view('pages.tours.index', compact('tours', 'categories', 'cities', 'wishlistedIds', 'durationOptions', 'priceRange', 'seasonOptions'));
    }

    public function show(string $slug)
    {
        $tour = Tour::where('slug', $slug)->where('is_active', true)
            ->with(['category', 'cities', 'images', 'itineraries.hotel', 'pricingTiers', 'dates' => fn ($q) => $q->where('is_active', true)->where('date', '>=', now())->orderBy('date')])
            ->withCount(['approvedReviews'])
            ->firstOrFail();

        $tour->load('approvedReviews.user');

        $userHasReviewed = auth()->check()
            ? $tour->reviews()->where('user_id', auth()->id())->exists()
            : false;

        return view('pages.tours.show', compact('tour', 'userHasReviewed'));
    }
}
