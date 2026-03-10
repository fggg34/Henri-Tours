@extends('emails.layout')

@section('title', 'Welcome - ' . config('app.name'))

@section('header')
<div style="background: linear-gradient(135deg, #4d7c0f 0%, #65a30d 50%, #84cc16 100%); color: #ffffff; padding: 28px 24px; text-align: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700;">Account created successfully</h1>
    <p style="margin: 8px 0 0; opacity: 0.95; font-size: 15px;">{{ config('app.name') }}</p>
</div>
@endsection

@section('content')
<p style="margin: 0 0 20px; font-size: 15px;">Hello <strong>{{ $user->name }}</strong>,</p>
<p style="margin: 0 0 24px; color: #6b7280;">Thank you for creating an account with us. Your registration was successful and you can now:</p>

<ul style="margin: 0 0 24px; padding-left: 20px; color: #4b5563; font-size: 14px; line-height: 1.8;">
    <li>Browse and book tours</li>
    <li>Manage your bookings in your dashboard</li>
    <li>Save tours to your wishlist</li>
    <li>Leave reviews after your trips</li>
</ul>

<div style="margin-top: 24px; text-align: center;">
    <a href="{{ route('dashboard') }}" class="btn">Go to my dashboard</a>
</div>
<div style="margin-top: 12px; text-align: center;">
    <a href="{{ route('tours.index') }}" style="color: #65a30d; font-weight: 600; font-size: 14px;">Browse tours</a>
</div>
@endsection
