<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected function findBookingByToken(string $token): Booking
    {
        $booking = Booking::where('confirmation_token', $token)->firstOrFail();
        $this->authorize('view', $booking);
        return $booking;
    }

    public function cancelByToken(string $token)
    {
        $booking = $this->findBookingByToken($token);
        $this->authorize('update', $booking);
        if ($booking->status === 'cancelled') {
            return back()->with('message', 'Booking is already cancelled.');
        }
        $booking->update(['status' => 'cancelled']);
        if ($booking->tourDate) {
            $booking->tourDate->increment('available_slots', $booking->guest_count);
        }
        return redirect()->route('dashboard')->with('success', 'Booking cancelled.');
    }
}
