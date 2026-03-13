@extends('emails.layout')

@section('title', 'Booking Confirmation - ' . $booking->tour->title)

@section('header')
<div style="background: linear-gradient(135deg, #4d7c0f 0%, #65a30d 50%, #84cc16 100%); color: #ffffff; padding: 28px 24px; text-align: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700;">Booking Confirmed</h1>
    <p style="margin: 8px 0 0; opacity: 0.95; font-size: 15px;">{{ config('app.name') }}</p>
    <p style="margin: 16px 0 0; font-size: 13px; opacity: 0.9;">Reference: {{ $booking->reference }}</p>
</div>
@endsection

@section('content')
<p style="margin: 0 0 20px; font-size: 15px;">Hello <strong>{{ $booking->guest_name }}</strong>,</p>
<p style="margin: 0 0 24px; color: #6b7280;">Thank you for your booking. We're excited to have you join us. Here are your confirmation details:</p>

<table class="email-table">
    <tr class="detail-row">
        <td class="detail-label">Tour</td>
        <td class="detail-value"><strong>{{ $booking->tour->title }}</strong></td>
    </tr>
    <tr class="detail-row">
        <td class="detail-label">Date</td>
        <td class="detail-value">{{ ($booking->booking_date ?? $booking->tourDate?->date)?->format('l, F j, Y') }}</td>
    </tr>
    <tr class="detail-row">
        <td class="detail-label">Travelers</td>
        <td class="detail-value">{{ $booking->guest_count }} {{ Str::plural('guest', $booking->guest_count) }}</td>
    </tr>
    @if($booking->tour->translate('start_time'))
    <tr class="detail-row">
        <td class="detail-label">Start time</td>
        <td class="detail-value">{{ $booking->tour->translate('start_time') }}</td>
    </tr>
    @endif
    @if($booking->tour->translate('start_location'))
    <tr class="detail-row">
        <td class="detail-label">Meeting point</td>
        <td class="detail-value">{{ $booking->tour->translate('start_location') }}</td>
    </tr>
    @endif
    @if($booking->pickup_location)
    <tr class="detail-row">
        <td class="detail-label">Pickup location</td>
        <td class="detail-value">{{ $booking->pickup_location }}</td>
    </tr>
    @endif
    <tr class="detail-row">
        <td class="detail-label">Status</td>
        <td class="detail-value"><span style="background: #ecfccb; color: #3f6212; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ ucfirst($booking->status) }}</span></td>
    </tr>
</table>

<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top: 24px; padding: 20px; background: #ecfccb; border-radius: 12px; border-left: 4px solid #84cc16;">
    <tr>
        <td style="font-size: 16px; font-weight: 600; color: #3f6212; text-align: left; padding: 12px;">Total amount</td>
        <td style="font-size: 22px; font-weight: 700; color: #1f2937; text-align: right; padding: 12px;">{{ (strtoupper($booking->currency ?? '') === 'EUR' ? '€' : ($booking->currency ?? '')) }}{{ number_format($booking->total_amount, 2) }}</td>
    </tr>
</table>

@if($booking->special_requests)
<p style="margin-top: 24px; padding: 16px; background: #f9fafb; border-radius: 8px; font-size: 14px; color: #4b5563;">
    <strong>Your special requests:</strong><br>{{ $booking->special_requests }}
</p>
@endif

<p style="margin-top: 24px; font-size: 14px; color: #6b7280;">We'll contact you if we need any further information. You can view your booking anytime using the link below.</p>

<div style="margin-top: 24px; text-align: center;">
    <a href="{{ $booking->confirmation_url }}" class="btn">View booking confirmation</a>
</div>
<div style="margin-top: 12px; text-align: center;">
    <a href="{{ route('tours.show', $booking->tour->slug) }}" style="color: #65a30d; font-weight: 600; font-size: 14px;">View tour details</a>
</div>
@endsection
