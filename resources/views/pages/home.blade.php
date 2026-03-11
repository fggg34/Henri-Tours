@extends('layouts.site')

@section('title', \App\Models\Setting::get('homepage_seo_title') ?: (config('app.name') . ' - Book Your Day Trips & Vacation Packages'))
@section('description', \App\Models\Setting::get('homepage_seo_description') ?: 'As Albania\'s leading travel agency, we bring you the most exciting travel packages.')

@section('hero')
@php
    $hero = $hero ?? null;
    $heroTitle = $hero?->title ?? \App\Models\Setting::get('hero_title', config('app.name') . ' - Book Your Day Trips & Vacation Packages');
    $heroSubtitle = \App\Models\Setting::get('hero_subtitle', "As Albania's leading travel agency, we bring you the most exciting travel packages, for your ideal Albanian adventure.");
    $bgImage = $hero && $hero->banner_type === 'image' && $hero->banner_image ? $hero->banner_image_url : 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=80';
    $bgVideo = $hero && $hero->banner_type === 'video' && $hero->banner_video ? $hero->banner_video_url : null;
@endphp
<section class="relative w-full min-h-[520px] md:min-h-[75vh] flex items-center justify-center">
    {{-- Background --}}
    @if($bgVideo)
        <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover"><source src="{{ $bgVideo }}" type="video/mp4"></video>
    @else
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $bgImage }}');"></div>
    @endif
    <div class="absolute inset-0 bg-black/40"></div>

    {{-- Content --}}
    <div class="relative z-10 w-full max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-4">{{ $heroTitle }}</h1>
        <p class="text-base md:text-lg text-white/90 max-w-2xl mx-auto mb-8 leading-relaxed">{{ $heroSubtitle }}</p>

        {{-- Trust badges --}}
        <div class="flex items-center justify-center gap-6 md:gap-10 mb-10">
            <img src="https://albaniainbound.com/wp-content/uploads/2026/01/Albania-leading-travel-agency.svg" alt="Albania's leading Travel agency" class="h-14 md:h-[72px] w-auto" />
            <img src="https://albaniainbound.com/wp-content/uploads/2026/01/Best-Price.svg" alt="Best price guarantee" class="h-14 md:h-[72px] w-auto" />
        </div>

        <x-hero-search-form :action="route('tours.index')" :cities="$cities ?? collect()" />
    </div>
</section>

{{-- Global info bar --}}
<div class="bg-brand-trust text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-4 py-3 text-xs md:text-sm">

            {{-- Book with only a 10% deposit --}}
            <div class="group relative flex items-center gap-2 md:gap-3 cursor-default">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.999 511.999" class="w-4 h-4" fill="currentColor">
                        <g>
                            <g>
                                <path d="M399.647,227.207c-53.203,0-93.887,22.533-93.887,84.499v88.88c0,61.966,40.684,84.499,93.887,84.499
                                    c52.576,0,93.887-22.533,93.887-84.499v-88.88C493.535,249.74,452.223,227.207,399.647,227.207z M430.943,400.587L430.943,400.587
                                    c0,20.654-11.892,30.043-31.295,30.043c-19.403,0-30.67-9.389-30.67-30.043v-88.88c0-20.656,11.267-30.044,30.67-30.044
                                    c19.403,0,31.295,9.389,31.295,30.044V400.587z"></path>
                            </g>
                        </g>
                        <g>
                            <g>
                                <path d="M363.345,0c-11.267,0-21.282,5.007-26.289,15.648L117.359,466.933c-1.878,3.756-3.13,8.137-3.13,11.892
                                    c0,15.648,13.77,33.174,35.052,33.174c11.892,0,23.785-6.259,28.166-15.648L397.77,45.066c1.877-3.756,2.502-8.137,2.502-11.893
                                    C400.272,13.144,380.869,0,363.345,0z"></path>
                            </g>
                        </g>
                        <g>
                            <g>
                                <path d="M112.351,25.662c-53.203,0-93.887,22.533-93.887,84.499v88.88c0,61.966,40.685,84.499,93.887,84.499
                                    c52.577,0,93.887-22.534,93.887-84.499v-88.88C206.239,48.195,164.929,25.662,112.351,25.662z M143.648,199.042
                                    c0,20.656-11.892,30.043-31.295,30.043c-19.403,0-30.67-9.389-30.67-30.043v-88.88c0-20.656,11.267-30.044,30.67-30.044
                                    c19.403,0,31.295,9.389,31.295,30.044V199.042z"></path>
                            </g>
                        </g>
                    </svg>
                </span>
                <button type="button" class="inline-flex items-center gap-1 font-semibold">
                    <span>Book with only a 10% deposit</span>
                    <svg class="w-3 h-3 text-white/70" viewBox="0 0 12 12" fill="none"><path fill="currentColor" fill-opacity="0.7" fill-rule="evenodd" d="M6 0a6 6 0 106 6 6.007 6.007 0 00-6-6m0 9.5A.75.75 0 116 8a.75.75 0 010 1.5m.5-2.581a.5.5 0 01.3-.459A2 2 0 104 4.629a.5.5 0 001 0 1 1 0 111.4.915 1.5 1.5 0 00-.9 1.375.5.5 0 001 0" clip-rule="evenodd"/></svg>
                </button>
                <div class="pointer-events-none absolute left-0 top-full mt-2 w-72 md:w-80 rounded-md bg-white text-gray-800 shadow-xl px-3 py-3 text-xs leading-relaxed opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-150 z-20">
                    Secure your booking with a small payment and pay the remaining balance only when the tour starts.
                </div>
            </div>

            {{-- Easy Booking & Cancellation --}}
            <div class="group relative flex items-center gap-2 md:gap-3 cursor-default">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-white/10">
                    <svg viewBox="0 0 24 25" class="w-4 h-4">
                        <path fill="currentColor" fill-rule="evenodd" d="M21.5.7h-19A2.5 2.5 0 000 3.2v19a2.5 2.5 0 002.5 2.5h19a2.5 2.5 0 002.5-2.5v-19A2.5 2.5 0 0021.5.7zm-3 9.5H14a1 1 0 110-2h4.5a1 1 0 110 2zm0 9a1 1 0 100-2H14a1 1 0 100 2zM11.3 6.3l-3 4a1 1 0 01-.729.4H7.5a1 1 0 01-.707-.293l-1.5-1.5a1 1 0 111.414-1.415l.685.685L9.7 5.1a1 1 0 111.6 1.2zm-3 14l3-4a1 1 0 00-1.6-1.2l-2.308 3.078-.685-.685a1 1 0 00-1.414 1.414l1.5 1.5A1.018 1.018 0 008.3 20.3z" clip-rule="evenodd"/>
                    </svg>
                </span>
                <button type="button" class="inline-flex items-center gap-1 font-semibold">
                    <span>Easy Booking &amp; Cancellation</span>
                    <svg class="w-3 h-3 text-white/70" viewBox="0 0 12 12" fill="none"><path fill="currentColor" fill-opacity="0.7" fill-rule="evenodd" d="M6 0a6 6 0 106 6 6.007 6.007 0 00-6-6m0 9.5A.75.75 0 116 8a.75.75 0 010 1.5m.5-2.581a.5.5 0 01.3-.459A2 2 0 104 4.629a.5.5 0 001 0 1 1 0 111.4.915 1.5 1.5 0 00-.9 1.375.5.5 0 001 0" clip-rule="evenodd"/></svg>
                </button>
                <div class="pointer-events-none absolute left-0 top-full mt-2 w-72 md:w-80 rounded-md bg-white text-gray-800 shadow-xl px-3 py-3 text-xs leading-relaxed opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-150 z-20">
                    Multi-day tour deposits are fully refundable up to 1 month before travel and can be easily cancelled online.
                </div>
            </div>

            {{-- Flexible & Confirmed Departures --}}
            <div class="group relative flex items-center gap-2 md:gap-3 cursor-default">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-white/10">
                    <svg viewBox="0 0 512 512" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="m512 112v65h-512v-65c0-33.1 26.9-60 60-60h35v23c0 19.3 15.7 35 35 35s35-15.7 35-35v-23h182v23c0 19.3 15.7 35 35 35s35-15.7 35-35v-23h35c33.1 0 60 26.9 60 60zm-130-12c13.8 0 25-11.2 25-25v-50c0-13.8-11.2-25-25-25s-25 11.2-25 25v50c0 13.8 11.2 25 25 25zm-252 0c13.8 0 25-11.2 25-25v-50c0-13.8-11.2-25-25-25s-25 11.2-25 25v50c0 13.8 11.2 25 25 25zm-130 87h512v265c0 33.1-26.9 60-60 60h-392c-33.1 0-60-26.9-60-60zm386 80c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm-110-205c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm-110-205c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm-110-205c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10z"/>
                    </svg>
                </span>
                <button type="button" class="inline-flex items-center gap-1 font-semibold">
                    <span>Flexible &amp; Confirmed Departures</span>
                    <svg class="w-3 h-3 text-white/70" viewBox="0 0 12 12" fill="none"><path fill="currentColor" fill-opacity="0.7" fill-rule="evenodd" d="M6 0a6 6 0 106 6 6.007 6.007 0 00-6-6m0 9.5A.75.75 0 116 8a.75.75 0 010 1.5m.5-2.581a.5.5 0 01.3-.459A2 2 0 104 4.629a.5.5 0 001 0 1 1 0 111.4.915 1.5 1.5 0 00-.9 1.375.5.5 0 001 0" clip-rule="evenodd"/></svg>
                </button>
                <div class="pointer-events-none absolute left-0 top-full mt-2 w-72 md:w-80 rounded-md bg-white text-gray-800 shadow-xl px-3 py-3 text-xs leading-relaxed opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-150 z-20">
                    Choose from several confirmed departures each month and pick the date that works best for you. Instantly confirmed and easy to reschedule.
                </div>
            </div>

            {{-- Trusted by Travelers --}}
            <div class="group relative flex items-center gap-2 md:gap-3 cursor-default">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-white/10">
                    <svg viewBox="0 0 428.16 428.16" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M393.8,110.208c-0.512-11.264-0.512-22.016-0.512-32.768c0-8.704-6.656-15.36-15.36-15.36c-64,0-112.64-18.432-153.088-57.856c-6.144-5.632-15.36-5.632-21.504,0C162.888,43.648,114.248,62.08,50.248,62.08c-8.704,0-15.36,6.656-15.36,15.36c0,10.752,0,21.504-0.512,32.768c-2.048,107.52-5.12,254.976,174.592,316.928l5.12,1.024l5.12-1.024C398.408,365.184,395.848,218.24,393.8,110.208z M201.8,259.2c-3.072,2.56-6.656,4.096-10.752,4.096h-0.512c-4.096,0-8.192-2.048-10.752-5.12l-47.616-52.736l23.04-20.48l37.376,41.472l82.944-78.848l20.992,22.528L201.8,259.2z"/>
                    </svg>
                </span>
                <button type="button" class="inline-flex items-center gap-1 font-semibold">
                    <span>Trusted by Travelers</span>
                    <svg class="w-3 h-3 text-white/70" viewBox="0 0 12 12" fill="none"><path fill="currentColor" fill-opacity="0.7" fill-rule="evenodd" d="M6 0a6 6 0 106 6 6.007 6.007 0 00-6-6m0 9.5A.75.75 0 116 8a.75.75 0 010 1.5m.5-2.581a.5.5 0 01.3-.459A2 2 0 104 4.629a.5.5 0 001 0 1 1 0 111.4.915 1.5 1.5 0 00-.9 1.375.5.5 0 001 0" clip-rule="evenodd"/></svg>
                </button>
                <div class="pointer-events-none absolute left-0 top-full mt-2 w-72 md:w-80 rounded-md bg-white text-gray-800 shadow-xl px-3 py-3 text-xs leading-relaxed opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-150 z-20">
                    We’ve served over 20,000 travelers in the past 2 years and received more than 2,000 reviews online.
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('content')

{{-- Suggested Tours --}}
@php
    $allTours = \App\Models\Tour::where('is_active', true)
        ->with(['category', 'images', 'approvedReviews'])
        ->orderBy('sort_order')
        ->limit(8)
        ->get();
@endphp
@if($allTours->isNotEmpty())
<section class="py-14 md:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-10">Suggested Tours</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($allTours->take(4) as $tour)
                <x-tour-card :tour="$tour" />
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Albania Inbound Section --}}
<section class="bg-[#f5f3ef] py-14 md:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-stretch gap-10 lg:gap-10 bg-white rounded-2xl shadow-md">

            {{-- Left: Team photo slider (Swiper) --}}
            <div class="relative flex-shrink-0 w-full lg:w-[600px] lg:self-stretch">
                <div class="swiper inbound-swiper h-full rounded-l-xl overflow-hidden">
                    <div class="swiper-wrapper h-full">
                        <div class="swiper-slide h-full">
                            <img src="https://albaniainbound.com/wp-content/uploads/2026/01/LAE9207-scaled-1-1-1.webp"
                                alt="Albania Inbound Team"
                                class="w-full h-full object-cover"
                                loading="lazy" />
                        </div>
                        <div class="swiper-slide h-full">
                            <img src="https://albaniainbound.com/wp-content/uploads/2026/02/AD108803.JPG-1.webp"
                                alt="Albania Inbound Team"
                                class="w-full h-full object-cover"
                                loading="lazy" />
                        </div>
                    </div>
                </div>

                <button data-inbound-prev class="absolute z-10 left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/90 hover:bg-white text-gray-700 shadow flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>
                <button data-inbound-next class="absolute z-10 right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/90 hover:bg-white text-gray-700 shadow flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
            </div>

            {{-- Right: Content --}}
            <div class="flex-1 min-w-0 pt-0 lg:pt-[30px] pb-[30px] pr-[30px]">
                <h3 class="text-2xl md:text-[34px] font-extrabold text-gray-900 mb-2 leading-tight">Albania Inbound</h3>
                <p class="text-[20px] text-gray-500 mb-7 leading-relaxed">Trusted experts for vacation packages, day trips, group tours and activities in Albania!</p>

                {{-- Checkmark items - 2 columns --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 mb-8">
                    <div class="flex items-center gap-2">
                        <svg class="w-[18px] h-[18px] text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-[15px] text-gray-700 font-medium">Best Selection of Tours Expertly Crafted</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-[18px] h-[18px] text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-[15px] text-gray-700 font-medium">Easy Booking & Free Cancelations</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-[18px] h-[18px] text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-[15px] text-gray-700 font-medium">Expert Travel Agents and Local Guidance</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-[18px] h-[18px] text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-[15px] text-gray-700 font-medium">English Customer Service</span>
                    </div>
                </div>

                {{-- Platform rating cards --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                    {{-- Google --}}
                    <div class="bg-white rounded-lg border border-gray-200 px-3 py-4 text-center">
                        <p class="text-[11px] font-semibold text-gray-600 mb-2.5 leading-tight">Google Top Rated<br>Service</p>
                        <div class="flex items-center justify-center gap-1.5 mb-1.5">
                            <i class="fa-brands fa-google text-base text-[#4285F4]"></i>
                            <span class="text-base font-bold text-gray-900">4.9</span>
                        </div>
                        <div class="flex items-center justify-center gap-0.5 mb-1.5">
                            <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                            <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                            <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                            <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                            <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                        </div>
                        <p class="text-[11px] text-gray-400">(84 reviews)</p>
                    </div>
                    {{-- TripAdvisor --}}
                    <div class="bg-white rounded-lg border border-gray-200 px-3 py-4 text-center">
                        <p class="text-[11px] font-semibold text-gray-600 mb-2.5 leading-tight">TripAdvisor<br>Travelers' Favorite</p>
                        <div class="flex items-center justify-center gap-1.5 mb-1.5">
                            <span class="w-5 h-5 rounded-full bg-[#34E0A1] flex items-center justify-center"><i class="fa-solid fa-comment-dots text-white text-[9px]"></i></span>
                            <span class="text-base font-bold text-gray-900">4.8</span>
                        </div>
                        <div class="flex items-center justify-center gap-0.5 mb-1.5">
                            <i class="fa-solid fa-circle text-[#34E0A1] text-[6px]"></i>
                            <i class="fa-solid fa-circle text-[#34E0A1] text-[6px]"></i>
                            <i class="fa-solid fa-circle text-[#34E0A1] text-[6px]"></i>
                            <i class="fa-solid fa-circle text-[#34E0A1] text-[6px]"></i>
                            <i class="fa-solid fa-circle text-[#34E0A1] text-[6px]"></i>
                        </div>
                        <p class="text-[11px] text-gray-400">(221 reviews)</p>
                    </div>
                    {{-- GetYourGuide --}}
                    <div class="bg-white rounded-lg border border-gray-200 px-3 py-4 text-center">
                        <p class="text-[11px] font-semibold text-gray-600 mb-2.5 leading-tight">GetYourGuide Top<br>Rated Experience</p>
                        <div class="flex items-center justify-center gap-1.5 mb-1.5">
                            <span class="w-5 h-5 rounded-full bg-[#FF5533] flex items-center justify-center"><i class="fa-solid fa-ticket text-white text-[9px]"></i></span>
                            <span class="text-base font-bold text-gray-900">4.9</span>
                        </div>
                        <div class="flex items-center justify-center gap-0.5 mb-1.5">
                            <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                            <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                            <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                            <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                            <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                        </div>
                        <p class="text-[11px] text-gray-400">(1,045 reviews)</p>
                    </div>
                    {{-- Facebook --}}
                    <div class="bg-white rounded-lg border border-gray-200 px-3 py-4 text-center">
                        <p class="text-[11px] font-semibold text-gray-600 mb-2.5 leading-tight">Facebook<br>Customer Favorite</p>
                        <div class="flex items-center justify-center gap-1.5 mb-1.5">
                            <i class="fa-brands fa-facebook text-base text-[#1877F2]"></i>
                            <span class="text-base font-bold text-gray-900">4.8</span>
                        </div>
                        <div class="flex items-center justify-center gap-0.5 mb-1.5">
                            <i class="fa-solid fa-star text-[#1877F2] text-[10px]"></i>
                            <i class="fa-solid fa-star text-[#1877F2] text-[10px]"></i>
                            <i class="fa-solid fa-star text-[#1877F2] text-[10px]"></i>
                            <i class="fa-solid fa-star text-[#1877F2] text-[10px]"></i>
                            <i class="fa-solid fa-star text-[#1877F2] text-[10px]"></i>
                        </div>
                        <p class="text-[11px] text-gray-400">(43 reviews)</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Featured Destinations (Tabbed by Category) --}}
@php
    $categories = \App\Models\TourCategory::orderBy('sort_order')
        ->with(['tours' => fn ($q) => $q->where('is_active', true)->with(['images', 'approvedReviews', 'category'])->orderBy('sort_order')->limit(12)])
        ->get();
@endphp
@if($categories->isNotEmpty())
<section class="py-14 md:py-20" x-data="{ activeTab: '{{ $categories->first()->slug }}' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-8">Featured Destinations</h2>

        {{-- Tabs --}}
        <div class="flex items-center justify-center gap-2 mb-10">
            @foreach($categories as $cat)
                <button @click="activeTab = '{{ $cat->slug }}'"
                    :class="activeTab === '{{ $cat->slug }}' ? 'bg-brand-navy text-white' : 'bg-white text-gray-700 border-gray-200 hover:border-brand-navy hover:text-brand-navy'"
                    class="px-5 py-2.5 text-sm font-semibold rounded-full border transition-colors cursor-pointer">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        {{-- Tab Panels --}}
        @foreach($categories as $cat)
            <div x-show="activeTab === '{{ $cat->slug }}'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                @if($cat->tours->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($cat->tours->take(8) as $tour)
                            <x-tour-card :tour="$tour" />
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-400 py-12">No tours available in this category yet.</p>
                @endif
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- Private Group Tours CTA --}}
<section class="bg-[#f5f3ef] py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center gap-10 md:gap-16">
            <div class="flex-shrink-0 w-48 md:w-64">
                <img src="https://albaniainbound.com/wp-content/uploads/2025/01/Asset-3.svg" alt="Albania Globe Illustration" class="w-full h-auto" />
            </div>
            <div class="text-center md:text-left">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-4">Private Group Tours: Travel Better, Together</p>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8 leading-tight">We're here to do good by creating positive change through the joy of travel.</h2>
                <a href="{{ route('contact') }}" class="inline-flex items-center px-8 py-3 border-2 border-gray-800 text-gray-800 hover:bg-gray-800 hover:text-white font-semibold rounded-md transition-colors">
                    View More
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Blog Section --}}
@if($latestPosts->isNotEmpty())
<section class="py-14 md:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-10">Get inspired on The Good Times</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($latestPosts as $post)
                <x-blog-card :post="$post" />
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- {{-- Testimonials --}}
@if(isset($testimonials) && $testimonials->isNotEmpty())
<section class="bg-gray-50 py-14 md:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400 mb-1">What our clients say</p>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Over {{ number_format($totalReviews ?? $testimonials->count()) }}+ Happy Travellers</h2>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" class="testimonials-prev w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-brand-navy hover:border-brand-navy transition-colors">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </button>
                <button type="button" class="testimonials-next w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-brand-navy hover:border-brand-navy transition-colors">
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </button>
            </div>
        </div>
        <div class="swiper testimonials-swiper">
            <div class="swiper-wrapper pb-2">
                @foreach($testimonials as $review)
                @php
                    $reviewerName = $review->reviewer_name ?? $review->user?->name ?? 'Anonymous';
                    $words = explode(' ', trim($reviewerName), 2);
                    $initials = count($words) >= 2
                        ? strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1))
                        : strtoupper(mb_substr($reviewerName, 0, 2));
                    $tourTitle = $review->tour?->title ?? null;
                    $rating = (int) ($review->rating ?? 5);
                @endphp
                <div class="swiper-slide" style="height: auto;">
                    <div class="flex flex-col h-full bg-white rounded-xl border border-gray-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-0.5 mb-3">
                            @for($i = 0; $i < $rating; $i++)
                                <i class="fa-solid fa-star text-amber-400 text-sm"></i>
                            @endfor
                            @for($i = $rating; $i < 5; $i++)
                                <i class="fa-regular fa-star text-gray-300 text-sm"></i>
                            @endfor
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed flex-1">{{ Str::limit($review->comment, 200) }}</p>
                        <div class="flex items-center gap-3 mt-5 pt-4 border-t border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-brand-navy/10 flex items-center justify-center text-brand-navy text-sm font-bold flex-shrink-0">
                                {{ $initials }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900">{{ $reviewerName }}</p>
                                @if($tourTitle)
                                    <p class="text-xs text-gray-400 truncate">{{ $tourTitle }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif -->

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.Swiper && document.querySelector('.testimonials-swiper')) {
        new window.Swiper('.testimonials-swiper', {
            modules: [window.SwiperNavigation],
            slidesPerView: 1.1,
            spaceBetween: 20,
            navigation: { prevEl: '.testimonials-prev', nextEl: '.testimonials-next' },
            breakpoints: {
                640: { slidesPerView: 2.2, spaceBetween: 20 },
                1024: { slidesPerView: 3.2, spaceBetween: 20 },
                1280: { slidesPerView: 4, spaceBetween: 20 },
            },
        });
    }

    if (window.Swiper && document.querySelector('.inbound-swiper')) {
        new window.Swiper('.inbound-swiper', {
            modules: [window.SwiperNavigation],
            slidesPerView: 1,
            spaceBetween: 0,
            navigation: {
                prevEl: '[data-inbound-prev]',
                nextEl: '[data-inbound-next]',
            },
        });
    }
});
</script>
@endpush
@endsection
