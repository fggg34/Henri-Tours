@extends('emails.layout')

@section('title', 'New Account Created - ' . config('app.name'))

@section('header')
<div style="background: linear-gradient(135deg, #1f2937 0%, #374151 100%); color: #ffffff; padding: 24px; text-align: center;">
    <h1 style="margin: 0; font-size: 20px; font-weight: 700;">New account created</h1>
    <p style="margin: 8px 0 0; font-size: 14px; opacity: 0.9;">{{ $user->name }} · {{ $user->email }}</p>
    <p style="margin: 4px 0 0; font-size: 12px; opacity: 0.8;">{{ $user->created_at->format('l, F j, Y \a\t g:i A') }}</p>
</div>
@endsection

@section('content')
<p style="margin: 0 0 20px; font-size: 14px;">A new user has registered on your site. Details below:</p>

<table class="email-table">
    <tr><td colspan="2" style="padding: 8px 16px; background: #f9fafb; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280;">Account information</td></tr>
    <tr class="detail-row">
        <td class="detail-label">Name</td>
        <td class="detail-value">{{ $user->name }}</td>
    </tr>
    <tr class="detail-row">
        <td class="detail-label">Email</td>
        <td class="detail-value"><a href="mailto:{{ $user->email }}" style="color: #65a30d;">{{ $user->email }}</a></td>
    </tr>
    <tr class="detail-row">
        <td class="detail-label">Registered at</td>
        <td class="detail-value">{{ $user->created_at->format('l, F j, Y \a\t g:i A') }}</td>
    </tr>
</table>

<div style="margin-top: 24px; text-align: center;">
    <a href="{{ url(config('app.filament_admin_path', '_panel') . '/users') }}" class="btn">View in admin panel</a>
</div>
@endsection

@section('footer')
<div class="footer">
    Automated notification from <strong>{{ config('app.name') }}</strong>.
</div>
@endsection
