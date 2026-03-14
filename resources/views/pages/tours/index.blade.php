@extends('layouts.site')

@section('title', 'Tours - ' . config('app.name'))
@section('description', 'Browse our selection of tours and book your next adventure.')

@php
    $heroTitle = \App\Models\Setting::get('page_tours_hero_title', 'Best Tours & Vacation Packages in Albania - Best Selection & Lowest Prices Guaranteed');
    $heroSubtitle = \App\Models\Setting::get('page_tours_hero_subtitle', 'Choose from a wide range of tours, activities, and vacation packages across Albania and the Balkan region.');
    $heroImage = \App\Models\Setting::get('page_tours_hero_image', '');
    $heroBg = $heroImage ? \Illuminate\Support\Facades\Storage::disk('public')->url($heroImage) : 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=80';
@endphp
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

{{-- Explore ways to travel --}}
<section class="py-10">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl md:text-3xl text-center mb-10 md:mb-14 text-gray-400 font-light">Explore <span class="font-bold text-gray-900 italic">ways to travel</span></h2>

        @if($categories->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ min($categories->count(), 4) }} gap-6 md:gap-8">
            @foreach($categories as $cat)
            <a href="{{ localized_route('tours.category', ['category' => $cat->slug]) }}" class="group block bg-white rounded-xl border border-gray-200 shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="aspect-[4/3] overflow-hidden bg-gray-100">
                    @if($cat->image_url)
                        <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <i class="fa-solid fa-mountain-sun text-4xl text-gray-400"></i>
                        </div>
                    @endif
                </div>
                <div class="p-5 text-center">
                    <h3 class="text-base md:text-lg font-bold text-gray-900 mb-1">{{ $cat->name }}</h3>
                    @if($cat->description)
                        <div class="text-sm text-gray-500 mb-4 prose prose-sm max-w-none dark:prose-invert">
                            {!! \Filament\Forms\Components\RichEditor\RichContentRenderer::make($cat->description)->toUnsafeHtml() !!}
                        </div>
                    @endif
                    <span class="inline-block px-6 py-2.5 bg-brand-btn text-white text-sm font-semibold rounded-full hover:bg-brand-btn-hover transition-colors">View All</span>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- Organized Group Tours / Confirmed Departures --}}
<section class="pb-14 md:pb-20">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 md:p-12">
            <div class="flex flex-col lg:flex-row items-center gap-10 lg:gap-16">

                <div class="flex-1">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 leading-snug mb-6">Organized Group Tours<br>With Confirmed Departure Dates</h2>

                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-sm md:text-base text-gray-700">Confirmed multi-day group tours</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-sm md:text-base text-gray-700">Fixed start dates</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-sm md:text-base text-gray-700">Guaranteed departures</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-sm md:text-base text-gray-700">Perfect for solo travelers</span>
                        </li>
                    </ul>

                    <a href="{{ localized_route('confirmed-departures') }}" class="inline-block px-6 py-2.5 bg-brand-btn text-white text-sm font-semibold rounded-full hover:bg-brand-btn-hover transition-colors">View Available Dates</a>
                </div>

                <div class="flex-1 max-w-md lg:max-w-lg">
                    <img src="https://albaniainbound.com/wp-content/uploads/2026/02/AD108803.JPG-1.webp" alt="Organized Group Tours" class="w-full rounded-xl shadow-lg rotate-2 hover:rotate-0 transition-transform duration-300" />
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
