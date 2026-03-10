<?php

namespace App\Contracts;

use App\Models\Booking;

interface PaymentServiceInterface
{
    /**
     * Create a checkout session for the booking.
     * Returns redirect URL or session ID for the client.
     */
    public function createCheckoutSession(Booking $booking): string;

    /**
     * Handle successful payment (e.g. webhook).
     */
    public function handleSuccessfulPayment(string $sessionId): void;

    /**
     * Whether the payment gateway is configured and active.
     */
    public function isActive(): bool;
}
