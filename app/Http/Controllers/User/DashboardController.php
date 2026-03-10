<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $bookings = $user->bookings()->with(['tour', 'tourDate'])->orderByDesc('created_at')->paginate(10);
        $wishlistTours = $user->wishlistTours()->with(['category', 'images'])->get();
        $activeBookingsCount = $user->bookings()->where('status', '!=', 'cancelled')->count();

        return view('dashboard', compact('bookings', 'wishlistTours', 'activeBookingsCount'));
    }
}
