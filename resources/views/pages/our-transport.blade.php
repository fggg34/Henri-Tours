@php
    $heroTitle = \App\Models\Setting::get('page_our_transport_hero_title', 'Our Transport');
    $heroSubtitle = \App\Models\Setting::get('page_our_transport_hero_subtitle', 'Travel comfortably across Albania with our modern fleet. From minivans to coaches, we ensure a smooth ride for every journey.');
    $heroImage = \App\Models\Setting::get('page_our_transport_hero_image', '');
    $heroBg = $heroImage ? \Illuminate\Support\Facades\Storage::disk('public')->url($heroImage) : 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=80';

    $seoTitle = \App\Models\Setting::get('page_our_transport_seo_title', '');
    $seoDesc = \App\Models\Setting::get('page_our_transport_seo_description', '');
@endphp
@extends('layouts.site')

@section('title', $seoTitle ?: ('Our Transport - ' . config('app.name')))
@section('description', $seoDesc ?: 'Travel comfortably across Albania with our modern fleet. From minivans to coaches, we ensure a smooth ride for every journey.')
@if(\App\Models\Setting::get('page_our_transport_seo_og_image'))
@section('og_image', \App\Models\Setting::get('page_our_transport_seo_og_image'))
@endif

@section('hero')
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 pt-6">
    <section class="relative w-full h-[75vh] flex items-center justify-center rounded-2xl overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $heroBg }}');"></div>
        <div class="absolute inset-0 bg-black/40 rounded-2xl"></div>

        <div class="relative z-10 w-full px-4 sm:px-6 lg:px-8 py-16 text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-4">{{ $heroTitle }}</h1>
            <p class="text-base md:text-lg text-white/90 max-w-2xl mx-auto mb-8 leading-relaxed">{{ $heroSubtitle }}</p>

            <div class="flex items-center justify-center gap-6 md:gap-10">
                <img src="https://albaniainbound.com/wp-content/uploads/2026/01/Albania-leading-travel-agency.svg" alt="Albania's leading Travel agency" class="h-14 md:h-[72px] w-auto" />
                <img src="https://albaniainbound.com/wp-content/uploads/2026/01/Best-Price.svg" alt="Best price guarantee" class="h-14 md:h-[72px] w-auto" />
            </div>
        </div>
    </section>
</div>

<x-global-info-bar variant="light" />
@endsection

@section('content')
{{-- Further sections will be added in the next step --}}
@endsection
