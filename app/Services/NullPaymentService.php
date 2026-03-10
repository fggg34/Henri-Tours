<?php

namespace App\Services;

use App\Contracts\PaymentServiceInterface;
use App\Models\Booking;

class NullPaymentService implements PaymentServiceInterface
{
    public function createCheckoutSession(Booking $booking): string
    {
        // When Stripe is enabled, redirect to Stripe Checkout.
        // For now, booking is confirmed without online payment.
        return $booking->confirmation_url;
    }

    public function handleSuccessfulPayment(string $sessionId): void
    {
        // Resolve booking from Stripe session and update payment_status.
        // To enable: install stripe/stripe-php, implement StripePaymentService.
    }

    public function isActive(): bool
    {
        return false;
    }
}
