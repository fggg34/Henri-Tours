<?php

namespace App\Http\Controllers;

use App\Models\PrivateGroupTourRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PrivateGroupTourRequestsController extends Controller
{
    public function index()
    {
        return view('pages.private-group-tour-requests');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|max:50',
            'expected_departure_date' => 'required|date',
            'expected_return_date' => 'required|date|after_or_equal:expected_departure_date',
            'number_of_participants' => 'required|integer|min:1|max:999',
            'departing_from' => 'required|string|max:255',
            'additional_info' => 'nullable|string|max:5000',
        ]);

        PrivateGroupTourRequest::create($validated);

        $successMessage = Setting::get('page_private_group_tour_requests_form_success_message', 'Thank you! Your request has been submitted. We\'ll get back to you soon.');

        return back()->with('success', $successMessage);
    }
}
