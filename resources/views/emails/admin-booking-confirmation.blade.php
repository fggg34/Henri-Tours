@extends('emails.layout')

@section('title', 'New Booking #' . $booking->id . ' - ' . config('app.name'))

@section('header')
<div style="background: linear-gradient(135deg, #1f2937 0%, #374151 100%); color: #ffffff; padding: 24px; text-align: center;">
    <h1 style="margin: 0; font-size: 20px; font-weight: 700;">New booking received</h1>
    <p style="margin: 8px 0 0; font-size: 14px; opacity: 0.9;">Reference #{{ $booking->id }} · {{ $booking->tour->title }}</p>
    <p style="margin: 4px 0 0; font-size: 12px; opacity: 0.8;">{{ ($booking->booking_date ?? $booking->tourDate?->date)?->format('l, F j, Y') }}</p>
</div>
@endsection

@section('content')
<p style="margin: 0 0 20px; font-size: 14px;">A new booking has been made. Review the details below:</p>

<table class="email-table">
    <tr><td colspan="2" style="padding: 8px 16px; background: #f9fafb; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280;">Guest information</td></tr>
    <tr class="detail-row">
        <td class="detail-label">Name</td>
        <td class="detail-value">{{ $booking->guest_name }}</td>
    </tr>
    <tr class="detail-row">
        <td class="detail-label">Email</td>
        <td class="detail-value"><a href="mailto:{{ $booking->guest_email }}" style="color: #65a30d;">{{ $booking->guest_email }}</a></td>
    </tr>
    @if($booking->guest_phone)
    <tr class="detail-row">
        <td class="detail-label">Phone</td>
        <td class="detail-value">{{ $booking->guest_phone }}</td>
    </tr>
    @endif
    @if($booking->pickup_location)
    <tr class="detail-row">
        <td class="detail-label">Pickup location</td>
        <td class="detail-value">{{ $booking->pickup_location }}</td>
    </tr>
    @endif

    <tr><td colspan="2" style="padding: 8px 16px; background: #f9fafb; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280;">Booking details</td></tr>
    <tr class="detail-row">
        <td class="detail-label">Tour</td>
        <td class="detail-value"><strong>{{ $booking->tour->title }}</strong></td>
    </tr>
    <tr class="detail-row">
        <td class="detail-label">Date</td>
        <td class="detail-value">{{ ($booking->booking_date ?? $booking->tourDate?->date)?->format('l, F j, Y') }}</td>
    </tr>
    <tr class="detail-row">
        <td class="detail-label">Number of guests</td>
        <td class="detail-value">{{ $booking->guest_count }}</td>
    </tr>
    <tr class="detail-row">
        <td class="detail-label">Status</td>
        <td class="detail-value">{{ ucfirst($booking->status) }}</td>
    </tr>

    @if($booking->billing_address || $booking->billing_city || $booking->billing_country)
    <tr><td colspan="2" style="padding: 8px 16px; background: #f9fafb; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280;">Billing address</td></tr>
    <tr class="detail-row">
        <td class="detail-label">Address</td>
        <td class="detail-value">
            @if($booking->billing_address){{ $booking->billing_address }}<br>@endif
            @if($booking->billing_city || $booking->billing_region || $booking->billing_country)
                {{ $booking->billing_city }}{{ $booking->billing_city && ($booking->billing_region || $booking->billing_country) ? ', ' : '' }}{{ $booking->billing_region }}{{ $booking->billing_region && $booking->billing_country ? ' ' : '' }}{{ $booking->billing_country }}
            @endif
        </td>
    </tr>
    @endif

    @if($booking->special_requests)
    <tr><td colspan="2" style="padding: 8px 16px; background: #f9fafb; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280;">Special requests</td></tr>
    <tr class="detail-row">
        <td class="detail-label">Notes</td>
        <td class="detail-value">{{ $booking->special_requests }}</td>
    </tr>
    @endif
</table>

<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top: 20px; padding: 20px; background: #ecfccb; border-radius: 12px; border-left: 4px solid #84cc16;">
    <tr>
        <td style="font-size: 16px; font-weight: 600; color: #3f6212; text-align: left; padding: 12px;">Total amount</td>
        <td style="font-size: 20px; font-weight: 700; color: #1f2937; text-align: right; padding: 12px;">{{ (strtoupper($booking->currency ?? '') === 'EUR' ? '€' : ($booking->currency ?? '')) }}{{ number_format($booking->total_amount, 2) }}</td>
    </tr>
</table>

<div style="margin-top: 24px; padding: 16px; background: #f9fafb; border-radius: 8px; font-size: 13px; color: #6b7280;">
    <strong>Quick actions:</strong> Reply to this email to contact the guest directly.
</div>
<div style="margin-top: 12px; text-align: center;">
    <a href="{{ url(config('app.filament_admin_path', 'admin') . '/bookings/' . $booking->id . '/edit') }}" class="btn">View in admin panel</a>
</div>
@endsection

@section('footer')
<div class="footer">
    Automated notification from <strong>{{ config('app.name') }}</strong>. Reply to contact the guest.
</div>
@endsection
