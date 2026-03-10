<?php

namespace App\Services;

use App\Models\Tour;
use App\Models\TourAvailability;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class TourAvailabilityService
{
    /**
     * Get available dates for a tour within its availability range.
     * Respects available_weekdays, closed_dates, and capacity.
     */
    public function getAvailableDates(Tour $tour, ?Carbon $from = null, ?Carbon $to = null): Collection
    {
        $start = $from ?? now()->startOfDay();
        $end = $to ?? $tour->availability_end_date?->endOfDay() ?? now()->addMonths(3)->endOfDay();

        if ($tour->availability_start_date) {
            $rangeStart = Carbon::parse($tour->availability_start_date)->startOfDay();
            if ($start->lt($rangeStart)) {
                $start = $rangeStart;
            }
        }
        if ($tour->availability_end_date) {
            $rangeEnd = Carbon::parse($tour->availability_end_date)->endOfDay();
            if ($end->gt($rangeEnd)) {
                $end = $rangeEnd;
            }
        }

        $closedDates = collect($tour->closed_dates ?? [])->map(fn ($d) => Carbon::parse($d)->format('Y-m-d'));
        $weekdaysRaw = $tour->available_weekdays;
        $weekdays = is_array($weekdaysRaw) && count($weekdaysRaw) > 0
            ? array_map('intval', $weekdaysRaw)
            : [0, 1, 2, 3, 4, 5, 6];
        $defaultCapacity = $tour->default_daily_capacity ?? $tour->max_group_size ?? 20;

        $availabilities = TourAvailability::where('tour_id', $tour->id)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->keyBy(fn ($a) => $a->date->format('Y-m-d'));

        $dates = collect();
        $current = $start->copy();

        while ($current->lte($end)) {
            $dateStr = $current->format('Y-m-d');
            if ($closedDates->contains($dateStr)) {
                $current->addDay();
                continue;
            }
            if (! in_array((int) $current->dayOfWeek, $weekdays)) {
                $current->addDay();
                continue;
            }

            $av = $availabilities->get($dateStr);
            $capacity = $av ? $av->capacity : $defaultCapacity;
            $booked = $av ? $av->booked_spots : 0;
            $availableSpots = max(0, $capacity - $booked);

            $dates->push((object) [
                'date' => $dateStr,
                'date_formatted' => $current->format('Y-m-d'),
                'available_spots' => $availableSpots,
                'capacity' => $capacity,
                'is_available' => $availableSpots > 0,
            ]);

            $current->addDay();
        }

        return $dates;
    }

    /**
     * Ensure an availability row exists for tour+date and return it.
     * Uses whereDate for lookup so we match existing rows regardless of date format (e.g. Y-m-d vs Y-m-d H:i:s in SQLite).
     */
    public function getOrCreateAvailability(Tour $tour, string $date): TourAvailability
    {
        $dateStr = Carbon::parse($date)->toDateString();
        $av = TourAvailability::where('tour_id', $tour->id)
            ->whereDate('date', $dateStr)
            ->first();
        if ($av) {
            return $av;
        }
        try {
            return TourAvailability::create([
                'tour_id' => $tour->id,
                'date' => $dateStr,
                'capacity' => $tour->default_daily_capacity ?? $tour->max_group_size ?? 20,
                'booked_spots' => 0,
            ]);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            return TourAvailability::where('tour_id', $tour->id)
                ->whereDate('date', $dateStr)
                ->firstOrFail();
        } catch (QueryException $e) {
            if ($e->getCode() === '23000' || ($e->errorInfo[0] ?? null) === '23000') {
                return TourAvailability::where('tour_id', $tour->id)
                    ->whereDate('date', $dateStr)
                    ->firstOrFail();
            }
            throw $e;
        }
    }

    /**
     * Check if a date is bookable (in range, weekday, not closed, has spots).
     */
    public function isDateBookable(Tour $tour, string $date, int $guestCount = 1): bool
    {
        $carbon = Carbon::parse($date);
        $dateStr = $carbon->format('Y-m-d');

        if ($tour->closed_dates && in_array($dateStr, $tour->closed_dates, true)) {
            return false;
        }
        $weekdaysRaw = $tour->available_weekdays;
        $weekdays = is_array($weekdaysRaw) && count($weekdaysRaw) > 0
            ? array_map('intval', $weekdaysRaw)
            : [0, 1, 2, 3, 4, 5, 6];
        if (! in_array((int) $carbon->dayOfWeek, $weekdays)) {
            return false;
        }
        if ($tour->availability_start_date && $carbon->lt(Carbon::parse($tour->availability_start_date))) {
            return false;
        }
        if ($tour->availability_end_date && $carbon->gt(Carbon::parse($tour->availability_end_date))) {
            return false;
        }

        $av = TourAvailability::where('tour_id', $tour->id)->where('date', $dateStr)->first();
        $capacity = $av ? $av->capacity : ($tour->default_daily_capacity ?? $tour->max_group_size ?? 20);
        $booked = $av ? $av->booked_spots : 0;
        return ($capacity - $booked) >= $guestCount;
    }
}
