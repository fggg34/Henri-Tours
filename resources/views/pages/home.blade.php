@extends('layouts.site')

@php $locale = app()->getLocale(); @endphp
@section('title', \App\Models\Setting::getTranslated('homepage_seo_title', $locale, '') ?: (config('app.name') . ' - Book Your Day Trips & Vacation Packages'))
@section('description', \App\Models\Setting::getTranslated('homepage_seo_description', $locale, '') ?: 'As Albania\'s leading travel agency, we bring you the most exciting travel packages.')

@section('hero')
@php
    $hero = $hero ?? null;
    $heroTitle = $hero?->translate('title') ?? \App\Models\Setting::get('hero_title', config('app.name') . ' - Book Your Day Trips & Vacation Packages');
    $heroSubtitle = $hero?->translate('subtitle') ?? \App\Models\Setting::get('hero_subtitle', "As Albania's leading travel agency, we bring you the most exciting travel packages, for your ideal Albanian adventure.");
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

        <x-hero-search-form :action="route('tours.search')" :cities="$cities ?? collect()" />
    </div>
</section>

<x-global-info-bar />
@endsection

@section('content')

{{-- Homepage Highlights --}}
@php
    $homepageHighlights = \App\Models\Highlight::with('cities')
        ->whereHas('cities')
        ->orderBy('sort_order')
        ->limit(6)
        ->get();
@endphp
@if($homepageHighlights->isNotEmpty())
<section class="py-10 md:py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-6 md:mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">{{ __('messages.top_things') }}</h2>
            <p class="text-xs md:text-sm text-gray-500 mt-1">{{ __('messages.book_with_best') }}</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4">
            @foreach($homepageHighlights as $highlight)
                @php $city = $highlight->cities->first(); @endphp
                <div class="relative rounded-2xl overflow-hidden shadow-sm group">
                    <div class="aspect-[4/3] w-full h-full">
                        @if($highlight->image_url)
                            <img src="{{ $highlight->image_url }}"
                                 alt="{{ $highlight->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                        @else
                            <div class="w-full h-full bg-gradient-to-tr from-sky-900 via-sky-600 to-emerald-500"></div>
                        @endif
                    </div>
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 p-2.5 sm:p-3 text-center">
                        <p class="text-[11px] sm:text-xs md:text-sm font-semibold text-white leading-snug drop-shadow-sm">
                            {{ $highlight->title }}
                        </p>
                    </div>
                    @if($city)
                        <a href="{{ route('cities.highlights.show', [$city->slug, $highlight->slug]) }}" class="absolute inset-0 z-10" aria-label="{{ $highlight->title }}"></a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

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
        <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-10">{{ __('messages.suggested_tours') }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($allTours->take(4) as $tour)
                <x-tour-card :tour="$tour" />
            @endforeach
        </div>
    </div>
</section>
@endif

@php
    $albaniaVisible = filter_var(\App\Models\Setting::get('homepage_albania_visible', true), FILTER_VALIDATE_BOOLEAN);
    $albaniaTitle = \App\Models\Setting::getTranslated('homepage_albania_title', $locale, 'Albania Inbound');
    $albaniaSubtitle = \App\Models\Setting::getTranslated('homepage_albania_subtitle', $locale, 'Trusted experts for vacation packages, day trips, group tours and activities in Albania!');
    $albaniaImages = \App\Models\Setting::get('homepage_albania_images', '');
    $albaniaImages = is_string($albaniaImages) ? (json_decode($albaniaImages, true) ?: []) : ($albaniaImages ?: []);
    if (empty($albaniaImages)) {
        $albaniaImages = [
            'https://albaniainbound.com/wp-content/uploads/2026/01/LAE9207-scaled-1-1-1.webp',
            'https://albaniainbound.com/wp-content/uploads/2026/02/AD108803.JPG-1.webp',
        ];
    }
    $albaniaCheckItems = \App\Models\Setting::get('homepage_albania_check_items', '');
    $albaniaCheckItems = is_string($albaniaCheckItems) ? (json_decode($albaniaCheckItems, true) ?: []) : ($albaniaCheckItems ?: []);
    if (empty($albaniaCheckItems)) {
        $albaniaCheckItems = [
            ['text' => 'Best Selection of Tours Expertly Crafted'],
            ['text' => 'Easy Booking & Free Cancelations'],
            ['text' => 'Expert Travel Agents and Local Guidance'],
            ['text' => 'English Customer Service'],
        ];
    }
    $albaniaPlatforms = \App\Models\Setting::getTranslated('homepage_albania_platforms', $locale, '');
    $albaniaPlatforms = is_string($albaniaPlatforms) ? (json_decode($albaniaPlatforms, true) ?: []) : ($albaniaPlatforms ?: []);
    if (empty($albaniaPlatforms)) {
        $albaniaPlatforms = [
            ['label' => "Google Top Rated\nService", 'rating' => '4.9', 'reviews' => '84', 'icon_type' => 'google'],
            ['label' => "TripAdvisor\nTravelers' Favorite", 'rating' => '4.8', 'reviews' => '221', 'icon_type' => 'tripadvisor'],
            ['label' => "GetYourGuide Top\nRated Experience", 'rating' => '4.9', 'reviews' => '1,045', 'icon_type' => 'getyourguide'],
            ['label' => "Facebook\nCustomer Favorite", 'rating' => '4.8', 'reviews' => '43', 'icon_type' => 'facebook'],
        ];
    }
@endphp
@if($albaniaVisible)
{{-- Albania Inbound Section --}}
<section class="bg-[#f5f3ef] py-14 md:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-stretch gap-10 lg:gap-10 bg-white rounded-2xl shadow-md">

            {{-- Left: Team photo slider (Swiper) --}}
            <div class="relative flex-shrink-0 w-full lg:w-[600px] lg:self-stretch">
                <div class="swiper inbound-swiper h-full rounded-l-xl overflow-hidden">
                    <div class="swiper-wrapper h-full">
                        @foreach($albaniaImages as $img)
                        @php $imgUrl = is_string($img) && str_starts_with($img, 'http') ? $img : \Illuminate\Support\Facades\Storage::disk('public')->url($img); @endphp
                        <div class="swiper-slide h-full">
                            <img src="{{ $imgUrl }}"
                                alt="{{ $albaniaTitle }}"
                                class="w-full h-full object-cover"
                                loading="lazy" />
                        </div>
                        @endforeach
                    </div>
                </div>

                @if(count($albaniaImages) > 1)
                <button data-inbound-prev class="absolute z-10 left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/90 hover:bg-white text-gray-700 shadow flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>
                <button data-inbound-next class="absolute z-10 right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/90 hover:bg-white text-gray-700 shadow flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
                @endif
            </div>

            {{-- Right: Content --}}
            <div class="flex-1 min-w-0 pt-0 lg:pt-[30px] pb-[30px] pr-[30px]">
                <h3 class="text-2xl md:text-[34px] font-extrabold text-gray-900 mb-2 leading-tight">{{ $albaniaTitle }}</h3>
                <p class="text-[20px] text-gray-500 mb-7 leading-relaxed">{{ $albaniaSubtitle }}</p>

                {{-- Checkmark items - 2 columns --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 mb-8">
                    @foreach($albaniaCheckItems as $item)
                    @if(!empty($item['text'] ?? ''))
                    <div class="flex items-center gap-2">
                        <svg class="w-[18px] h-[18px] text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-[15px] text-gray-700 font-medium">{{ $item['text'] }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>

                {{-- Platform rating cards --}}
                @if(count($albaniaPlatforms) > 0)
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                    @foreach($albaniaPlatforms as $platform)
                    @php $iconType = $platform['icon_type'] ?? 'google'; @endphp
                    <div class="bg-white rounded-lg border border-gray-200 px-3 py-4 text-center">
                        <p class="text-[11px] font-semibold text-gray-600 mb-2.5 leading-tight">{!! nl2br(e($platform['label'] ?? '')) !!}</p>
                        <div class="flex items-center justify-center gap-1.5 mb-1.5">
                            @if($iconType === 'google')<i class="fa-brands fa-google text-base text-[#4285F4]"></i>
                            @elseif($iconType === 'tripadvisor')<span class="w-5 h-5 rounded-full bg-[#34E0A1] flex items-center justify-center"><i class="fa-solid fa-comment-dots text-white text-[9px]"></i></span>
                            @elseif($iconType === 'getyourguide')<span class="w-5 h-5 rounded-full bg-[#FF5533] flex items-center justify-center"><i class="fa-solid fa-ticket text-white text-[9px]"></i></span>
                            @elseif($iconType === 'facebook')<i class="fa-brands fa-facebook text-base text-[#1877F2]"></i>
                            @else<i class="fa-solid fa-star text-amber-400 text-[10px]"></i>@endif
                            <span class="text-base font-bold text-gray-900">{{ $platform['rating'] ?? '' }}</span>
                        </div>
                        <div class="flex items-center justify-center gap-0.5 mb-1.5">
                            @if($iconType === 'tripadvisor')
                            @for($i = 0; $i < 5; $i++)<i class="fa-solid fa-circle text-[#34E0A1] text-[6px]"></i>@endfor
                            @elseif($iconType === 'facebook')
                            @for($i = 0; $i < 5; $i++)<i class="fa-solid fa-star text-[#1877F2] text-[10px]"></i>@endfor
                            @else
                            @for($i = 0; $i < 5; $i++)<i class="fa-solid fa-star text-amber-400 text-[10px]"></i>@endfor
                            @endif
                        </div>
                        @if(!empty($platform['reviews'] ?? ''))
                        <p class="text-[11px] text-gray-400">({{ $platform['reviews'] }} reviews)</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>
    </div>
</section>
@endif

{{-- Featured Destinations (Tabbed by Category) --}}
@php
    $categories = \App\Models\TourCategory::orderBy('sort_order')
        ->with(['tours' => fn ($q) => $q->where('is_active', true)->with(['images', 'approvedReviews', 'category'])->orderBy('sort_order')->limit(12)])
        ->get();
@endphp
@if($categories->isNotEmpty())
<section class="py-14 md:py-20" x-data="{ activeTab: '{{ $categories->first()->slug }}' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-8">{{ __('messages.featured_destinations') }}</h2>

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
                    <p class="text-center text-gray-400 py-12">{{ __('messages.no_tours_in_category') }}</p>
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
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-4">{{ __('messages.private_group_cta') }}</p>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8 leading-tight">{{ __('messages.private_group_title') }}</h2>
                <a href="{{ route('contact') }}" class="inline-flex items-center px-8 py-3 border-2 border-gray-800 text-gray-800 hover:bg-gray-800 hover:text-white font-semibold rounded-md transition-colors">
                    {{ __('messages.view_more') }}
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Blog Section --}}
@if($latestPosts->isNotEmpty())
<section class="py-14 md:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-10">{{ __('messages.get_inspired') }}</h2>
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
