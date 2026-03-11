@php
    $heroTitle = \App\Models\Setting::get('page_private_group_tour_requests_hero_title', 'Private Group Tour Requests');
    $heroSubtitle = \App\Models\Setting::get('page_private_group_tour_requests_hero_subtitle', 'Request a custom tour for your group. Tell us your dates, group size, and preferences – we\'ll create a tailored itinerary just for you.');
    $heroImage = \App\Models\Setting::get('page_private_group_tour_requests_hero_image', '');
    $heroBg = $heroImage ? \Illuminate\Support\Facades\Storage::disk('public')->url($heroImage) : 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=80';

    $seoTitle = \App\Models\Setting::get('page_private_group_tour_requests_seo_title', '');
    $seoDesc = \App\Models\Setting::get('page_private_group_tour_requests_seo_description', '');
    $introTitle = \App\Models\Setting::get('page_private_group_tour_requests_intro_title', 'Why choose Albania Inbound?');
    $introContent = \App\Models\Setting::get('page_private_group_tour_requests_intro_content', 'We offer fast, priority support for private group enquiries. Our dedicated travel agents will create a customized travel plan tailored to your group – no complex forms, no hassle.');
    $showMoreText = \App\Models\Setting::get('page_private_group_tour_requests_intro_show_more_text', 'Show more');
    $showMoreUrl = \App\Models\Setting::get('page_private_group_tour_requests_intro_show_more_url', '');
    $showMoreContent = \App\Models\Setting::get('page_private_group_tour_requests_intro_show_more_content', '');
    $featureCards = \App\Models\Setting::get('page_private_group_tour_requests_feature_cards', '');
    $featureCards = is_string($featureCards) ? (json_decode($featureCards, true) ?: []) : $featureCards;
    if (empty($featureCards)) {
        $featureCards = [
            ['icon' => 'fa-award', 'title' => 'Over a Decade of Excellence', 'description' => 'With years of experience, Albania Inbound delivers unforgettable journeys, making every trip extraordinary.'],
            ['icon' => 'fa-map-location-dot', 'title' => 'Inspiring Journeys', 'description' => "We go beyond the usual, offering immersive experiences that uncover Albania's hidden gems."],
            ['icon' => 'fa-handshake', 'title' => 'Travel with Purpose', 'description' => 'Committed to sustainability, we ensure every trip supports local communities and preserves culture.'],
        ];
    }
@endphp
@extends('layouts.site')

@section('title', $seoTitle ?: ('Private Group Tour Requests - ' . config('app.name')))
@section('description', $seoDesc ?: 'Request a custom tour for your group. Tell us your dates, group size, and preferences – we\'ll create a tailored itinerary just for you.')
@if(\App\Models\Setting::get('page_private_group_tour_requests_seo_og_image'))
@section('og_image', \App\Models\Setting::get('page_private_group_tour_requests_seo_og_image'))
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
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Intro Section --}}
    @if($introTitle || $introContent)
    <section class="py-14 md:py-20">
        <div class="max-w-3xl">
            @if($introTitle)
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 leading-tight">{{ $introTitle }}</h2>
            @endif
            @if($introContent)
                <div class="text-gray-600 leading-relaxed space-y-3" x-data="{ expanded: false }">
                    @foreach(array_filter(explode("\n\n", $introContent)) as $para)
                        <p>{{ $para }}</p>
                    @endforeach
                    @if($showMoreText && ($showMoreUrl || $showMoreContent))
                        @if($showMoreUrl)
                            <a href="{{ $showMoreUrl }}" class="inline-flex items-center gap-1.5 text-brand-navy hover:text-brand-btn font-medium text-sm transition-colors mt-3">
                                {{ $showMoreText }}
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        @else
                            <button type="button" @click="expanded = !expanded" class="inline-flex items-center gap-1.5 text-brand-navy hover:text-brand-btn font-medium text-sm transition-colors mt-3">
                                <span x-show="!expanded">{{ $showMoreText }}</span>
                                <span x-show="expanded" x-cloak>Show less</span>
                                <i class="fa-solid text-[10px]" :class="expanded ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </button>
                            @if($showMoreContent)
                                <div x-show="expanded" x-collapse class="mt-4 pt-4 border-t border-gray-100">
                                    @foreach(array_filter(explode("\n\n", $showMoreContent)) as $para)
                                        <p class="text-gray-600 mb-3 last:mb-0">{{ $para }}</p>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    @endif
                </div>
            @endif
        </div>
    </section>
    @endif

    {{-- Form Section --}}
    <section class="pb-16 md:pb-20">
        <div class="max-w-4xl mx-auto">
            <div class="bg-slate-50 rounded-2xl p-8 md:p-10 border border-gray-200 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Submit your enquiry</h3>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 text-green-800 rounded-xl border border-green-100 flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-green-500"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('private-group-tour-requests.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1.5">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required placeholder="First Name"
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 py-3 text-gray-900">
                            @error('first_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="number_of_participants" class="block text-sm font-medium text-gray-700 mb-1.5">What is the number of participants? <span class="text-red-500">*</span></label>
                            <input type="number" name="number_of_participants" id="number_of_participants" value="{{ old('number_of_participants') }}" min="1" required placeholder="e.g. 10"
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 py-3 text-gray-900">
                            @error('number_of_participants')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1.5">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required placeholder="Last Name"
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 py-3 text-gray-900">
                            @error('last_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="departing_from" class="block text-sm font-medium text-gray-700 mb-1.5">Departing From <span class="text-red-500">*</span></label>
                            <input type="text" name="departing_from" id="departing_from" value="{{ old('departing_from') }}" required placeholder="e.g. Tirana, Albania"
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 py-3 text-gray-900">
                            @error('departing_from')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="your@email.com"
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 py-3 text-gray-900">
                            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number <span class="text-red-500">*</span></label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="+1 (555) 000-0000"
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 py-3 text-gray-900">
                            @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="expected_departure_date" class="block text-sm font-medium text-gray-700 mb-1.5">Expected Departure Date <span class="text-red-500">*</span></label>
                            <input type="date" name="expected_departure_date" id="expected_departure_date" value="{{ old('expected_departure_date') }}" required
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 py-3 text-gray-900">
                            @error('expected_departure_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="expected_return_date" class="block text-sm font-medium text-gray-700 mb-1.5">Expected Return Date <span class="text-red-500">*</span></label>
                            <input type="date" name="expected_return_date" id="expected_return_date" value="{{ old('expected_return_date') }}" required
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 py-3 text-gray-900">
                            @error('expected_return_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="additional_info" class="block text-sm font-medium text-gray-700 mb-1.5">Share more information about your inquiry</label>
                        <textarea name="additional_info" id="additional_info" rows="5" placeholder="Tell us about your preferences, interests, group needs..."
                            class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 py-3 text-gray-900 resize-y">{{ old('additional_info') }}</textarea>
                        @error('additional_info')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <button type="submit" class="px-10 py-3.5 bg-brand-navy hover:bg-brand-btn text-white font-semibold rounded-lg transition-colors">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

</div>

{{-- Feature Cards --}}
@if(!empty($featureCards))
<section class="py-14 md:py-20 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-8">
            @foreach($featureCards as $card)
            <div class="text-center px-4">
                @if(!empty($card['icon']))
                    <div class="w-16 h-16 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center mx-auto mb-5">
                        <i class="fa-solid {{ $card['icon'] }} text-xl text-brand-navy"></i>
                    </div>
                @endif
                @if(!empty($card['title']))
                    <h4 class="text-base font-bold text-gray-900 mb-2">{{ $card['title'] }}</h4>
                @endif
                @if(!empty($card['description']))
                    <p class="text-gray-500 leading-relaxed text-sm max-w-xs mx-auto">{{ $card['description'] }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
