<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Services\TourAvailabilityService;
use App\Services\TourPricingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TourBookingApiController extends Controller
{
    public function __construct(
        private TourPricingService $pricingService,
        private TourAvailabilityService $availabilityService
    ) {}

    /**
     * Calculate price for a tour given guest count and optional date.
     * GET /api/tours/{tour}/price?guests=3&date=2025-03-15
     * When date is provided, date-based discounts are applied if applicable.
     */
    public function price(Request $request, string $slug): JsonResponse
    {
        $tour = Tour::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $guests = max(1, (int) $request->get('guests', 1));
        $maxGuests = $tour->max_group_size ?? 99;
        $guests = min($guests, $maxGuests);

        $date = $request->filled('date')
            ? \Carbon\Carbon::parse($request->get('date'))
            : null;

        $result = $this->pricingService->calculateForGuests($tour, $guests, $date);

        return response()->json([
            'price_per_person' => $result['price_per_person'],
            'total' => $result['total'],
            'original_price_per_person' => $result['original_price_per_person'] ?? $result['price_per_person'],
            'currency' => $result['currency'],
            'guest_count' => $result['guest_count'],
            'tier_applied' => $result['tier_applied'] ? [
                'min_people' => $result['tier_applied']->min_people,
                'max_people' => $result['tier_applied']->max_people,
                'price_per_person' => (float) $result['tier_applied']->price_per_person,
            ] : null,
            'discount_applied' => $result['discount_applied'] ?? null,
        ]);
    }

    /**
     * Get available dates for a tour.
     * GET /api/tours/{tour}/available-dates?from=2025-03-01&to=2025-04-30
     */
    public function availableDates(Request $request, string $slug): JsonResponse
    {
        $tour = Tour::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $from = $request->has('from') ? \Carbon\Carbon::parse($request->get('from')) : null;
        $to = $request->has('to') ? \Carbon\Carbon::parse($request->get('to')) : null;

        $dates = $this->availabilityService->getAvailableDates($tour, $from, $to);
        $closedDates = collect($tour->closed_dates ?? [])->map(fn ($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->values()->all();

        return response()->json([
            'dates' => $dates->values()->all(),
            'closed_dates' => $closedDates,
        ]);
    }

    /**
     * Check a specific date and guest count (spots left).
     * GET /api/tours/{tour}/check-date?date=2025-03-15&guests=2
     */
    public function checkDate(Request $request, string $slug): JsonResponse
    {
        $tour = Tour::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $date = $request->get('date');
        $guests = (int) $request->get('guests', 1);

        if (! $date) {
            return response()->json(['error' => 'Date required'], 422);
        }

        $bookable = $this->availabilityService->isDateBookable($tour, $date, $guests);
        $availability = $this->availabilityService->getOrCreateAvailability($tour, $date);
        $spotsLeft = $availability->available_spots;

        return response()->json([
            'date' => $date,
            'bookable' => $bookable,
            'spots_left' => $spotsLeft,
            'can_book' => $spotsLeft >= $guests,
        ]);
    }
}
