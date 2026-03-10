<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Tour;
use App\Models\TourDate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    public function __construct(
        private TourPricingService $pricingService,
        private TourAvailabilityService $availabilityService
    ) {}

    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            $tour = Tour::findOrFail($data['tour_id']);

            if (! empty($data['booking_date'])) {
                return $this->createBookingByDate($tour, $data);
            }

            $tourDate = TourDate::findOrFail($data['tour_date_id']);
            if ($tourDate->tour_id !== $tour->id) {
                throw new \InvalidArgumentException('Invalid tour date.');
            }
            if ($tourDate->available_slots < $data['guest_count']) {
                throw new \InvalidArgumentException('Not enough available slots for this date.');
            }

            $pricing = $this->pricingService->calculateForGuests($tour, $data['guest_count']);
            $expectedTotal = $pricing['total'];
            if (isset($data['expected_total']) && abs((float) $data['expected_total'] - $expectedTotal) > 0.01) {
                throw new \InvalidArgumentException('Price mismatch. Please refresh and try again.');
            }

            $booking = Booking::create([
                'confirmation_token' => Str::random(64),
                'user_id' => $data['user_id'] ?? null,
                'tour_id' => $tour->id,
                'tour_date_id' => $tourDate->id,
                'booking_date' => $tourDate->date,
                'status' => 'pending',
                'total_amount' => $expectedTotal,
                'currency' => $tour->currency,
                'guest_count' => $data['guest_count'],
                'guest_name' => $data['guest_name'],
                'guest_email' => $data['guest_email'],
                'first_name' => $data['first_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                'guest_phone' => $data['guest_phone'] ?? null,
                'pickup_location' => $data['pickup_location'] ?? null,
                'special_requests' => $data['special_requests'] ?? null,
                'billing_country' => $data['billing_country'] ?? null,
                'billing_region' => $data['billing_region'] ?? null,
                'billing_city' => $data['billing_city'] ?? null,
                'billing_address' => $data['billing_address'] ?? null,
                'payment_status' => 'pending',
            ]);

            $tourDate->decrement('available_slots', $data['guest_count']);

            return $booking->load(['tour', 'tourDate']);
        });
    }

    private function createBookingByDate(Tour $tour, array $data): Booking
    {
        $date = $data['booking_date'];
        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date)->toDateString();
        } else {
            $date = $date->format('Y-m-d');
        }

        if (! $this->availabilityService->isDateBookable($tour, $date, $data['guest_count'])) {
            throw new \InvalidArgumentException('Selected date is not available or has insufficient capacity.');
        }

        $pricing = $this->pricingService->calculateForGuests($tour, $data['guest_count']);
        $expectedTotal = $pricing['total'];
        if (isset($data['expected_total']) && abs((float) $data['expected_total'] - $expectedTotal) > 0.01) {
            throw new \InvalidArgumentException('Price mismatch. Please refresh and try again.');
        }

        $availability = $this->availabilityService->getOrCreateAvailability($tour, $date);

        $booking = Booking::create([
            'confirmation_token' => Str::random(64),
            'user_id' => $data['user_id'] ?? null,
            'tour_id' => $tour->id,
            'tour_date_id' => null,
            'booking_date' => $date,
            'status' => 'pending',
            'total_amount' => $expectedTotal,
            'currency' => $tour->currency,
            'guest_count' => $data['guest_count'],
            'guest_name' => $data['guest_name'],
            'guest_email' => $data['guest_email'],
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'guest_phone' => $data['guest_phone'] ?? null,
            'pickup_location' => $data['pickup_location'] ?? null,
            'special_requests' => $data['special_requests'] ?? null,
            'billing_country' => $data['billing_country'] ?? null,
            'billing_region' => $data['billing_region'] ?? null,
            'billing_city' => $data['billing_city'] ?? null,
            'billing_address' => $data['billing_address'] ?? null,
            'payment_status' => 'pending',
        ]);

        $availability->increment('booked_spots', $data['guest_count']);

        return $booking->load(['tour']);
    }
}
