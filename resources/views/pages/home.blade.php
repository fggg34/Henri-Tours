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
<section class="relative w-full min-h-[520px] md:min-h-[600px] flex items-center justify-center overflow-hidden">
    {{-- Background --}}
    @if($bgVideo)
        <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover"><source src="{{ $bgVideo }}" type="video/mp4"></video>
    @else
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $bgImage }}');"></div>
    @endif
    <div class="absolute inset-0 bg-black/40"></div>

    {{-- Content --}}
    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-4" style="font-family: 'Playfair Display', serif;">{{ $heroTitle }}</h1>
        <p class="text-base md:text-lg text-white/90 max-w-2xl mx-auto mb-10 leading-relaxed">{{ $heroSubtitle }}</p>

        <x-hero-search-form :action="route('tours.index')" :cities="$cities ?? collect()" />
    </div>
</section>

{{-- Trust Bar --}}
<div class="bg-brand-trust text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 divide-x divide-white/10">
            <div class="flex items-center gap-3 py-4 px-3 lg:px-5">
                <span class="text-2xl">🏷️</span>
                <div>
                    <p class="text-sm font-semibold">Book with only a 10% deposit</p>
                </div>
            </div>
            <div class="flex items-center gap-3 py-4 px-3 lg:px-5">
                <span class="text-2xl">📋</span>
                <div>
                    <p class="text-sm font-semibold">Easy Booking & Cancellation</p>
                </div>
            </div>
            <div class="flex items-center gap-3 py-4 px-3 lg:px-5">
                <span class="text-2xl">📅</span>
                <div>
                    <p class="text-sm font-semibold">Flexible & Confirmed Departures</p>
                </div>
            </div>
            <div class="flex items-center gap-3 py-4 px-3 lg:px-5">
                <span class="text-2xl">💙</span>
                <div>
                    <p class="text-sm font-semibold">Trusted by Travelers</p>
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
        <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-10" style="font-family: 'Playfair Display', serif;">Suggested Tours</h2>
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
        <div class="flex flex-col lg:flex-row items-center gap-10 lg:gap-16">

            {{-- Left: Team photo with slider arrows --}}
            <div class="relative flex-shrink-0 w-full lg:w-[420px]">
                <div class="rounded-2xl overflow-hidden">
                    <div class="w-full bg-gradient-to-br from-brand-navy to-brand-navy/70 flex items-center justify-center" style="aspect-ratio: 4/3;">
                        <div class="text-center text-white/80">
                            <i class="fa-solid fa-users text-5xl mb-3"></i>
                            <p class="text-sm font-medium">Team Photo</p>
                        </div>
                    </div>
                </div>
                <button class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/50 hover:bg-black/70 text-white flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </button>
                <button class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/50 hover:bg-black/70 text-white flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-chevron-right text-sm"></i>
                </button>
            </div>

            {{-- Right: Content --}}
            <div class="flex-1 min-w-0">
                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ config('app.name') }}</h3>
                <p class="text-gray-500 mb-6 leading-relaxed">Trusted experts for vacation packages, day trips, group tours and activities in Albania!</p>

                {{-- Checkmark items - 2 columns --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 mb-8">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-sm text-gray-700">Best Selection of Tours Expertly Crafted</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-sm text-gray-700">Easy Booking & Free Cancelations</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-sm text-gray-700">Expert Travel Agents and Local Guidance</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-sm text-gray-700">English Customer Service</span>
                    </div>
                </div>

                {{-- Platform rating cards --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                    {{-- Google --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
                        <p class="text-xs font-semibold text-gray-700 mb-2 leading-tight">Google Top Rated Service</p>
                        <div class="flex items-center justify-center gap-1.5 mb-1">
                            <i class="fa-brands fa-google text-lg text-[#4285F4]"></i>
                            <span class="text-lg font-bold text-gray-900">4.9</span>
                        </div>
                        <div class="flex items-center justify-center gap-0.5 mb-1">
                            <i class="fa-solid fa-star text-amber-500 text-xs"></i>
                            <i class="fa-solid fa-star text-amber-500 text-xs"></i>
                            <i class="fa-solid fa-star text-amber-500 text-xs"></i>
                            <i class="fa-solid fa-star text-amber-500 text-xs"></i>
                            <i class="fa-solid fa-star text-amber-500 text-xs"></i>
                        </div>
                        <p class="text-xs text-gray-400">(84 reviews)</p>
                    </div>
                    {{-- TripAdvisor --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
                        <p class="text-xs font-semibold text-gray-700 mb-2 leading-tight">TripAdvisor Travelers' Favorite</p>
                        <div class="flex items-center justify-center gap-1.5 mb-1">
                            <span class="w-5 h-5 rounded-full bg-[#34E0A1] flex items-center justify-center"><i class="fa-solid fa-comment-dots text-white text-[10px]"></i></span>
                            <span class="text-lg font-bold text-gray-900">4.8</span>
                        </div>
                        <div class="flex items-center justify-center gap-0.5 mb-1">
                            <i class="fa-solid fa-circle text-[#34E0A1] text-[7px]"></i>
                            <i class="fa-solid fa-circle text-[#34E0A1] text-[7px]"></i>
                            <i class="fa-solid fa-circle text-[#34E0A1] text-[7px]"></i>
                            <i class="fa-solid fa-circle text-[#34E0A1] text-[7px]"></i>
                            <i class="fa-solid fa-circle text-[#34E0A1] text-[7px]"></i>
                        </div>
                        <p class="text-xs text-gray-400">(221 reviews)</p>
                    </div>
                    {{-- GetYourGuide --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
                        <p class="text-xs font-semibold text-gray-700 mb-2 leading-tight">GetYourGuide Top Rated Experience</p>
                        <div class="flex items-center justify-center gap-1.5 mb-1">
                            <span class="w-5 h-5 rounded-full bg-[#FF5533] flex items-center justify-center"><i class="fa-solid fa-ticket text-white text-[10px]"></i></span>
                            <span class="text-lg font-bold text-gray-900">4.9</span>
                        </div>
                        <div class="flex items-center justify-center gap-0.5 mb-1">
                            <i class="fa-solid fa-star text-amber-500 text-xs"></i>
                            <i class="fa-solid fa-star text-amber-500 text-xs"></i>
                            <i class="fa-solid fa-star text-amber-500 text-xs"></i>
                            <i class="fa-solid fa-star text-amber-500 text-xs"></i>
                            <i class="fa-solid fa-star text-amber-500 text-xs"></i>
                        </div>
                        <p class="text-xs text-gray-400">(1,045 reviews)</p>
                    </div>
                    {{-- Facebook --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
                        <p class="text-xs font-semibold text-gray-700 mb-2 leading-tight">Facebook Customer Favorite</p>
                        <div class="flex items-center justify-center gap-1.5 mb-1">
                            <i class="fa-brands fa-facebook text-lg text-[#1877F2]"></i>
                            <span class="text-lg font-bold text-gray-900">4.8</span>
                        </div>
                        <div class="flex items-center justify-center gap-0.5 mb-1">
                            <i class="fa-solid fa-star text-[#1877F2] text-xs"></i>
                            <i class="fa-solid fa-star text-[#1877F2] text-xs"></i>
                            <i class="fa-solid fa-star text-[#1877F2] text-xs"></i>
                            <i class="fa-solid fa-star text-[#1877F2] text-xs"></i>
                            <i class="fa-solid fa-star text-[#1877F2] text-xs"></i>
                        </div>
                        <p class="text-xs text-gray-400">(43 reviews)</p>
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
        <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-8" style="font-family: 'Playfair Display', serif;">Featured Destinations</h2>

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
<section class="relative bg-brand-navy py-16 md:py-24 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-brand-navy via-brand-navy-light to-brand-navy opacity-90"></div>
    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-xs font-semibold uppercase tracking-wider text-white/60 mb-4">Private Group Tours: Travel Better, Together</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-8" style="font-family: 'Playfair Display', serif;">We're here to do good by creating positive change through the joy of travel.</h2>
        <a href="{{ route('contact') }}" class="inline-flex items-center px-8 py-3 bg-brand-btn hover:bg-brand-btn-hover text-white font-semibold rounded-md transition-colors">
            View More
        </a>
    </div>
</section>

{{-- Blog Section --}}
@if($latestPosts->isNotEmpty())
<section class="py-14 md:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-10" style="font-family: 'Playfair Display', serif;">Get inspired on The Good Times</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($latestPosts as $post)
                <x-blog-card :post="$post" />
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Testimonials --}}
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
@endif

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
});
</script>
@endpush
@endsection
