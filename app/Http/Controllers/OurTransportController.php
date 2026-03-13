<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\TransportBooking;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OurTransportController extends Controller
{
    public function index()
    {
        return view('pages.our-transport');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'telephone' => 'required|string|max:50',
            'travel_date' => 'required|date',
            'travel_end_date' => 'required|date|after_or_equal:travel_date',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'preferred_vehicle' => 'nullable|string|max:255',
            'group_size' => 'nullable|integer|min:1|max:999',
            'message' => 'nullable|string|max:5000',
        ]);

        TransportBooking::create($validated);

        $successMessage = Setting::getTranslated('page_our_transport_form_success_message', app()->getLocale(), 'Thank you! Your transport request has been submitted. We\'ll get back to you soon.');

        return back()->with('success', $successMessage);
    }
}
