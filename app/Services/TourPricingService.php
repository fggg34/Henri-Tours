<?php

namespace App\Services;

use App\Models\Tour;
use Carbon\Carbon;

class TourPricingService
{
    /**
     * Get price per person and total for a given number of travelers.
     * Optional $date: when provided, applies any matching date-based discount.
     * Returns: ['price_per_person' => float, 'total' => float, 'tier_applied' => ..., 'discount_applied' => ..., 'original_price_per_person' => float, 'currency' => string]
     */
    public function calculateForGuests(Tour $tour, int $guestCount, ?Carbon $date = null): array
    {
        $guestCount = max(1, $guestCount);
        $basePrice = (float) ($tour->base_price ?? $tour->price ?? 0);
        $currency = $tour->currency ?? 'EUR';

        $tier = $tour->pricingTiers
            ->sortByDesc('min_people')
            ->first(fn ($t) => $t->matches($guestCount));

        $pricePerPerson = $tier
            ? (float) $tier->price_per_person
            : $basePrice;

        $originalPricePerPerson = $pricePerPerson;
        $discountApplied = null;

        if ($date) {
            $tour->loadMissing('discounts');
            $discount = $tour->discounts->first(fn ($d) => $d->appliesOn($date));
            if ($discount) {
                $pricePerPerson = $discount->apply($pricePerPerson);
                $pricePerPerson = round($pricePerPerson, 2);
                $discountApplied = [
                    'type' => $discount->discount_type,
                    'value' => (float) $discount->discount_value,
                    'label' => $discount->label,
                ];
            }
        }

        $total = round($pricePerPerson * $guestCount, 2);

        return [
            'price_per_person' => $pricePerPerson,
            'total' => $total,
            'original_price_per_person' => $originalPricePerPerson,
            'tier_applied' => $tier,
            'discount_applied' => $discountApplied,
            'currency' => $currency,
            'guest_count' => $guestCount,
        ];
    }
}
