@extends('layouts.site')

@section('title', 'Complete your booking - ' . config('app.name'))

@section('content')
@php
    $displayDate = $booking_date ?? ($tourDate?->date?->format('l, F j, Y'));
    $dateForSummary = $booking_date ? \Carbon\Carbon::parse($booking_date) : $tourDate?->date;
    $displayDateTime = $dateForSummary ? $dateForSummary->format('d/m/Y') : '';
    $firstImage = $tour->images->first();
    $tourImageUrl = $firstImage && $firstImage->path ? $firstImage->url : 'https://placehold.co/1200x600?text=Tour';
    $durationDays = (int) ($tour->duration_days ?? 0);
    $durationLabel = $durationDays ? $durationDays . ' Day' . ($durationDays > 1 ? 's' : '') . ' Tour' : ($tour->duration_hours ? $tour->duration_hours . ' hours' : 'Tour');
    $total = $pricing['total'] ?? (($tourDate->price ?? $tour->base_price ?? $tour->price) * $guests);
@endphp
<div class="bg-gray-50 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">Complete your booking</h1>

        <form action="{{ route('bookings.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            @if($booking_date)
                <input type="hidden" name="booking_date" value="{{ $booking_date }}">
                <input type="hidden" name="tour_id" value="{{ $tour->id }}">
            @else
                <input type="hidden" name="tour_date_id" value="{{ $tourDate->id }}">
            @endif
            <input type="hidden" name="guest_count" value="{{ $guests }}">
            <input type="hidden" name="expected_total" value="{{ $total }}">

            {{-- Left column: Lead Traveler + Billing --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Lead Traveler --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <h2 class="font-semibold text-gray-900">Lead Traveler</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', auth()->user()?->name ? explode(' ', auth()->user()->name, 2)[0] : '') }}" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                            @error('first_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', auth()->user()?->name ? (explode(' ', auth()->user()->name, 2)[1] ?? '') : '') }}" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                            @error('last_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="guest_email" class="block text-sm font-medium text-gray-700 mb-1">Email address <span class="text-red-500">*</span></label>
                            <input type="email" name="guest_email" id="guest_email" value="{{ old('guest_email', auth()->user()?->email) }}" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900" placeholder="your@email.com">
                            @error('guest_email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="email_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm email <span class="text-red-500">*</span></label>
                            <input type="email" name="email_confirmation" id="email_confirmation" value="{{ old('email_confirmation', auth()->user()?->email) }}" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900" placeholder="your@email.com">
                            @error('email_confirmation')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="guest_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone number</label>
                            <input
                                type="tel"
                                name="guest_phone"
                                id="guest_phone"
                                value="{{ old('guest_phone') }}"
                                placeholder="67 212 3456"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900"
                                autocomplete="tel"
                                data-initial-phone="{{ e(old('guest_phone', '')) }}"
                            >
                            @error('guest_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="pickup_location" class="block text-sm font-medium text-gray-700 mb-1">Pickup location</label>
                            <input type="text" name="pickup_location" id="pickup_location" value="{{ old('pickup_location') }}" placeholder="Hotel name or address"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                            @error('pickup_location')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Billing Address --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <h2 class="font-semibold text-gray-900">Billing Address</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="billing_country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <select name="billing_country" id="billing_country" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                                <option value="">Select...</option>
                                @foreach($countries ?? [] as $country)
                                    <option value="{{ $country['name'] }}" {{ old('billing_country') === $country['name'] ? 'selected' : '' }}>{{ $country['name'] }}</option>
                                @endforeach
                            </select>
                            @error('billing_country')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="billing_region" class="block text-sm font-medium text-gray-700 mb-1">State / Region</label>
                            <input type="text" name="billing_region" id="billing_region" value="{{ old('billing_region') }}" placeholder="Enter state or region"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                            @error('billing_region')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="billing_city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input type="text" name="billing_city" id="billing_city" value="{{ old('billing_city') }}" placeholder="Enter city"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                            @error('billing_city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <input type="text" name="billing_address" id="billing_address" value="{{ old('billing_address') }}" placeholder="Street address"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                            @error('billing_address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <label for="special_requests" class="block text-sm font-medium text-gray-700 mb-1">Special requests</label>
                    <textarea name="special_requests" id="special_requests" rows="3" placeholder="Dietary requirements, accessibility needs, etc." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">{{ old('special_requests') }}</textarea>
                    @error('special_requests')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="w-full lg:w-auto lg:min-w-[280px] py-4 px-6 bg-brand-btn text-white font-semibold rounded-xl hover:bg-brand-btn-hover focus:outline-none focus:ring-2 focus:ring-brand-btn focus:ring-offset-2 transition">
                    Complete booking
                </button>
            </div>

            {{-- Right column: Tour Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm sticky top-24">
                    <div class="aspect-video bg-gray-200">
                        <img src="{{ $tourImageUrl }}" alt="{{ $tour->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-5 space-y-3">
                        <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ $tour->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $durationLabel }}</p>
                        @if($tour->approvedReviews->count() > 0)
                            <p class="flex items-center gap-2 text-sm">
                                <span class="flex text-amber-500">@for($i = 1; $i <= 5; $i++)<span>{{ $i <= round($tour->average_rating ?? 0) ? '★' : '☆' }}</span>@endfor</span>
                                <span class="text-gray-500">({{ $tour->approvedReviews->count() }} reviews)</span>
                            </p>
                        @endif
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $displayDateTime }} @php $startTime = $tour->translate('start_time'); @endphp @if($startTime) at {{ \Carbon\Carbon::parse($startTime)->format('g:i A') }} @else at 12:00 AM @endif
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $durationDays ? $durationDays . ' days duration' : ($tour->duration_hours ? $tour->duration_hours . ' hours' : '—') }}
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                {{ $guests }} {{ Str::plural('traveler', $guests) }}
                            </li>
                        </ul>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <span class="font-medium text-gray-700">Total</span>
                            <span class="text-xl font-bold text-gray-900">€{{ number_format($total, 0) }}</span>
                        </div>
                        <p class="flex items-center gap-2 text-sm text-gray-500">
                            <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Instant confirmation
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@26/build/css/intlTelInput.min.css">
<style>
.iti.iti--allow-dropdown.iti--show-flags.iti--inline-dropdown {
    width: 100%;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@26/build/js/intlTelInput.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var input = document.getElementById('guest_phone');
  if (!input || !window.intlTelInput) return;

  var initialNumber = input.getAttribute('data-initial-phone') || '';

  var iti = window.intlTelInput(input, {
    initialCountry: 'al',
    utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@26/build/js/utils.js'
  });

  if (initialNumber) {
    iti.setNumber(initialNumber);
  }

  var form = input.closest('form');
  if (form) {
    form.addEventListener('submit', function() {
      // Build full international number: +[dialCode][digitsOnly]
      try {
        var data = iti.getSelectedCountryData();
        var dialCode = data && data.dialCode ? '+' + data.dialCode : '';
        var digits = (input.value || '').replace(/\D/g, '');
        if (dialCode && digits) {
          input.value = dialCode + digits;
        }
      } catch (e) {
        // If anything goes wrong, submit the raw value.
      }
    });
  }
});
</script>
@endpush
@endsection
