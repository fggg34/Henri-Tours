<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Models\Tour;
use App\Models\TourDate;
use App\Services\BookingService;
use App\Services\TourAvailabilityService;
use App\Services\TourPricingService;
use Illuminate\Http\Request;
use League\ISO3166\ISO3166;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService,
        private TourAvailabilityService $availabilityService,
        private TourPricingService $pricingService
    ) {}

    public function create(Request $request, string $slug)
    {
        $tour = Tour::where('slug', $slug)->where('is_active', true)->with('pricingTiers')->firstOrFail();
        $maxGuests = $tour->max_group_size ?? 99;

        $dateParam = $request->get('date');
        $guestsParam = (int) $request->get('guests', 0);
        if ($dateParam && $guestsParam >= 1) {
            if (is_numeric($dateParam)) {
                $request->validate(['date' => 'required|exists:tour_dates,id', 'guests' => 'required|integer|min:1|max:' . $maxGuests]);
                $tourDate = TourDate::where('id', $dateParam)->where('tour_id', $tour->id)->where('is_active', true)->firstOrFail();
                if ($tourDate->available_slots < $guestsParam) {
                    return back()->withErrors(['date' => 'Not enough slots available for this date.'])->withInput();
                }
                $pricing = $this->pricingService->calculateForGuests($tour, $guestsParam);
                return view('pages.bookings.create', [
                    'tour' => $tour,
                    'tourDate' => $tourDate,
                    'booking_date' => null,
                    'guests' => $guestsParam,
                    'pricing' => $pricing,
                    'countries' => $this->countriesForSelect(),
                ]);
            }
            $request->validate(['date' => 'required|date|after_or_equal:today', 'guests' => 'required|integer|min:1|max:' . $maxGuests]);
            $dateStr = \Carbon\Carbon::parse($dateParam)->toDateString();
            if (! $this->availabilityService->isDateBookable($tour, $dateStr, $guestsParam)) {
                return back()->withErrors(['date' => 'Selected date is not available or has no capacity.'])->withInput();
            }
            $pricing = $this->pricingService->calculateForGuests($tour, $guestsParam);
            return view('pages.bookings.create', [
                'tour' => $tour,
                'tourDate' => null,
                'booking_date' => $dateStr,
                'guests' => $guestsParam,
                'pricing' => $pricing,
                'countries' => $this->countriesForSelect(),
            ]);
        }

        if ($request->has('tour_date_id') && $request->has('guests')) {
            $request->validate([
                'tour_date_id' => 'required|exists:tour_dates,id',
                'guests' => 'required|integer|min:1|max:' . $maxGuests,
            ]);
            $tourDate = TourDate::where('id', $request->tour_date_id)->where('tour_id', $tour->id)->where('is_active', true)->firstOrFail();
            if ($tourDate->available_slots < $request->guests) {
                return back()->withErrors(['date' => 'Not enough slots available for this date.']);
            }
            $pricing = $this->pricingService->calculateForGuests($tour, (int) $request->guests);
            return view('pages.bookings.create', [
                'tour' => $tour,
                'tourDate' => $tourDate,
                'booking_date' => null,
                'guests' => (int) $request->guests,
                'pricing' => $pricing,
                'countries' => $this->countriesForSelect(),
            ]);
        }

        return redirect()->route('tours.show', $slug)->withErrors(['date' => 'Please select a date and number of guests.']);
    }

    /**
     * @return array<int, array{name: string, alpha2: string}>
     */
    private function countriesForSelect(): array
    {
        $countries = (new ISO3166)->all();
        usort($countries, fn ($a, $b) => strcmp($a['name'], $b['name']));

        return $countries;
    }

    public function store(Request $request)
    {
        $rules = [
            'guest_count' => 'required|integer|min:1',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'guest_email' => 'required|email',
            'email_confirmation' => 'required|email|same:guest_email',
            // Store whatever the user (or JS widget) submits, usually full E.164 number.
            'guest_phone' => 'nullable|string|max:40',
            'pickup_location' => 'nullable|string|max:255',
            'billing_country' => 'nullable|string|max:100',
            'billing_region' => 'nullable|string|max:100',
            'billing_city' => 'nullable|string|max:100',
            'billing_address' => 'nullable|string|max:255',
            'special_requests' => 'nullable|string|max:2000',
            'expected_total' => 'required|numeric|min:0',
        ];
        if ($request->filled('booking_date')) {
            $rules['booking_date'] = 'required|date|after_or_equal:today';
            $rules['tour_id'] = 'required|exists:tours,id';
        } else {
            $rules['tour_date_id'] = 'required|exists:tour_dates,id';
        }
        $validated = $request->validate($rules);
        $validated['user_id'] = auth()->id();
        $validated['guest_name'] = trim($validated['first_name'] . ' ' . $validated['last_name']);
        unset($validated['email_confirmation']);
        if (isset($validated['tour_date_id'])) {
            $tourDate = TourDate::with('tour')->findOrFail($validated['tour_date_id']);
            $validated['tour_id'] = $tourDate->tour_id;
        }
        try {
            $booking = $this->bookingService->createBooking($validated);
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['date' => $e->getMessage()])->withInput();
        }
        try {
            \Illuminate\Support\Facades\Mail::to($booking->guest_email)->send(new BookingConfirmation($booking));
        } catch (\Throwable $e) {
            report($e);
        }
        $adminEmail = config('mail.admin_email');
        if ($adminEmail && $adminEmail !== $booking->guest_email) {
            try {
                \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\AdminBookingConfirmation($booking));
            } catch (\Throwable $e) {
                report($e);
            }
        }
        return redirect()->route('bookings.confirmation', ['token' => $booking->confirmation_token])->with('booking', $booking);
    }

    public function confirmation(string $token)
    {
        $booking = \App\Models\Booking::where('confirmation_token', $token)->firstOrFail();
        $booking->load(['tour', 'tourDate']);
        return view('pages.bookings.confirmation', ['booking' => $booking]);
    }
}
