<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ConfirmedDeparturesController extends Controller
{
    public function index()
    {
        $tours = Tour::where('is_active', true)
            ->has('discounts')
            ->with(['category', 'images', 'approvedReviews', 'discounts' => fn ($q) => $q->where('end_date', '>=', now())->orderBy('start_date')])
            ->get()
            ->filter(fn ($t) => $t->discounts->isNotEmpty());

        $toursData = $tours->values()->map(function ($tour) {
            $currency = ($tour->currency === 'EUR' || ! $tour->currency) ? '€' : $tour->currency;
            $basePrice = (float) ($tour->base_price ?? $tour->price ?? 0);

            return [
                'id' => $tour->id,
                'title' => $tour->title,
                'slug' => $tour->slug,
                'category' => $tour->category?->name,
                'image' => $tour->images->first()?->url ?? 'https://placehold.co/600x400/e2e8f0/64748b?text=Tour',
                'short_description' => $tour->short_description ?? \Illuminate\Support\Str::limit(strip_tags($tour->description), 120),
                'duration_label' => $tour->duration_days && $tour->duration_days > 1
                    ? $tour->duration_days . ' Days'
                    : ($tour->duration_hours ? $tour->duration_hours . ' Hours' : null),
                'currency' => $currency,
                'base_price' => $basePrice,
                'dates' => $tour->discounts->map(function ($d) use ($basePrice, $currency) {
                    $discounted = $d->apply($basePrice);
                    return [
                        'id' => $d->id,
                        'start' => $d->start_date->format('Y-m-d'),
                        'start_label' => $d->start_date->format('l d') . '  ' . $d->start_date->format('F Y'),
                        'end' => $d->end_date->format('Y-m-d'),
                        'end_label' => $d->end_date->format('l d') . '  ' . $d->end_date->format('F Y'),
                        'label' => $d->label,
                        'original' => $currency . ' ' . number_format($basePrice, 0),
                        'price' => $currency . ' ' . number_format($discounted, 0),
                    ];
                })->values()->toArray(),
            ];
        })->values();

        return view('pages.confirmed-departures', [
            'tours' => $tours->values(),
            'toursJson' => $toursData->toJson(),
            'toursData' => $toursData,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'discount_id' => 'required|exists:tour_discounts,id',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'room_option' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:5000',
        ]);

        $tour = Tour::find($validated['tour_id']);
        $discount = \App\Models\TourDiscount::find($validated['discount_id']);

        $to = Setting::get('contact_email', config('mail.from.address'));
        $body = "=== Tour Booking Inquiry (Confirmed Departures) ===\n\n"
            . "Tour: {$tour->title}\n"
            . "Departure: {$discount->start_date->format('d/m/Y')} - {$discount->end_date->format('d/m/Y')}\n"
            . ($discount->label ? "Label: {$discount->label}\n" : '')
            . "\n--- Personal Details ---\n"
            . "Name: {$validated['full_name']}\n"
            . "Email: {$validated['email']}\n"
            . ($validated['phone'] ? "Phone: {$validated['phone']}\n" : '')
            . ($validated['date_of_birth'] ? "DOB: {$validated['date_of_birth']}\n" : '')
            . "\n--- Contact Details ---\n"
            . ($validated['address'] ? "Address: {$validated['address']}\n" : '')
            . ($validated['city'] ? "City: {$validated['city']}\n" : '')
            . ($validated['country'] ? "Country: {$validated['country']}\n" : '')
            . ($validated['room_option'] ? "\nRoom Option: {$validated['room_option']}\n" : '')
            . ($validated['message'] ? "\nMessage:\n{$validated['message']}\n" : '');

        try {
            Mail::raw($body, fn ($m) => $m->to($to)->replyTo($validated['email'])->subject('Confirmed Departure Inquiry: ' . $tour->title));
        } catch (\Throwable $e) {
            throw ValidationException::withMessages(['email' => ['Unable to send inquiry. Please try again or contact us directly.']]);
        }

        return back()->with('success', 'Thank you! Your inquiry has been sent. We will get back to you shortly.');
    }
}
