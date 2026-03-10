@extends('layouts.site')

@section('title', $city->name . ' - ' . config('app.name'))
@section('description', Str::limit(strip_tags($city->description ?? ''), 160))

@push('meta')
<meta property="og:title" content="{{ $city->name }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($city->description ?? ''), 200) }}">
<meta property="og:url" content="{{ request()->url() }}">
@if($city->city_image_url)
<meta property="og:image" content="{{ request()->getSchemeAndHttpHost() . $city->city_image_url }}">
@endif
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-500 mb-8" aria-label="Breadcrumb">
        <ol class="flex items-center gap-1.5">
            <li><a href="{{ route('home') }}" class="text-brand-navy hover:text-brand-navy transition">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('cities.index') }}" class="text-brand-navy hover:text-brand-navy transition">Cities</a></li>
            <li>/</li>
            <li class="text-gray-700">{{ $city->name }}</li>
        </ol>
    </nav>

    @php
        $allImages = collect();
        if ($city->city_image_url) $allImages->push($city->city_image_url);
        if ($city->gallery_urls) $allImages = $allImages->merge($city->gallery_urls);
        $totalPhotos = $allImages->count();
        $thumbImages = $allImages->slice(1)->values();
    @endphp

    {{-- Hero: Gallery left + Title/Description right --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 mb-14">
        {{-- Left: Gallery (big image + thumbnails) --}}
        <div class="city-gallery">
            @if($allImages->isNotEmpty())
                <div class="relative rounded-2xl overflow-hidden bg-gray-200" style="aspect-ratio: 16/10;">
                    <a href="{{ $allImages[0] }}" class="glightbox block w-full h-full" data-gallery="city-gallery">
                        <img src="{{ $allImages[0] }}" alt="{{ $city->name }}" class="w-full h-full object-cover">
                    </a>
                    @if($totalPhotos > 1)
                        <div class="absolute bottom-4 right-4 flex items-center gap-2 px-3.5 py-2 rounded-lg bg-brand-navy/80 backdrop-blur-sm text-white text-sm font-medium pointer-events-none">
                            <i class="fa-regular fa-images"></i>
                            {{ $totalPhotos }} photos
                        </div>
                    @endif
                </div>

                @if($thumbImages->isNotEmpty())
                    <div class="grid grid-cols-4 gap-2 mt-2">
                        @foreach($thumbImages->take(4) as $url)
                            <a href="{{ $url }}" class="glightbox group block aspect-[4/3] rounded-xl overflow-hidden bg-gray-200" data-gallery="city-gallery">
                                <img src="{{ $url }}" alt="{{ $city->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Hidden lightbox links for remaining images --}}
                @foreach($allImages->slice(5) as $url)
                    <a href="{{ $url }}" class="glightbox hidden" data-gallery="city-gallery"></a>
                @endforeach
            @else
                <div class="rounded-2xl bg-gray-100 h-full flex items-center justify-center text-gray-400" style="min-height: 400px;">
                    No images available
                </div>
            @endif
        </div>

        {{-- Right: Title + Description --}}
        <div class="flex flex-col justify-center">
            @if($city->country)
                <p class="text-xs font-medium uppercase tracking-wider text-brand-navy mb-2">{{ $city->country }}</p>
            @endif
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-5">{{ $city->name }}</h1>
            @if($city->description)
                <div class="prose prose-gray max-w-none text-gray-600">
                    {!! $city->description !!}
                </div>
            @endif
            <div class="flex flex-wrap items-center gap-4 mt-6 pt-6 border-t border-gray-100">
                @if($city->tours->count())
                    <div class="flex items-center gap-2">
                        <span class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center"><i class="fa-solid fa-route text-brand-navy text-sm"></i></span>
                        <div>
                            <span class="text-lg font-bold text-gray-900">{{ $city->tours->where('is_active', true)->count() }}</span>
                            <span class="text-sm text-gray-500 ml-1">Tours</span>
                        </div>
                    </div>
                @endif
                @if($city->hotels->count())
                    <div class="flex items-center gap-2">
                        <span class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center"><i class="fa-solid fa-hotel text-brand-navy text-sm"></i></span>
                        <div>
                            <span class="text-lg font-bold text-gray-900">{{ $city->hotels->count() }}</span>
                            <span class="text-sm text-gray-500 ml-1">Hotels</span>
                        </div>
                    </div>
                @endif
                @if($city->highlights->count())
                    <div class="flex items-center gap-2">
                        <span class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center"><i class="fa-solid fa-camera text-brand-navy text-sm"></i></span>
                        <div>
                            <span class="text-lg font-bold text-gray-900">{{ $city->highlights->count() }}</span>
                            <span class="text-sm text-gray-500 ml-1">Attractions</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Places to visit --}}
    @if($city->highlights->isNotEmpty())
    <section class="mb-14 overflow-hidden">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400 mb-1">Explore</p>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Places to visit in {{ $city->name }}</h2>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" class="city-highlights-prev w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-brand-navy hover:border-blue-300 transition-colors">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </button>
                <button type="button" class="city-highlights-next w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-brand-navy hover:border-blue-300 transition-colors">
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </button>
            </div>
        </div>
        <div class="swiper city-highlights-swiper overflow-visible">
            <div class="swiper-wrapper">
                @foreach($city->highlights as $highlight)
                <div class="swiper-slide">
                    <a href="{{ route('cities.highlights.show', [$city->slug, $highlight->slug]) }}" class="group block relative rounded-xl overflow-hidden bg-gray-200" style="aspect-ratio: 4/3;">
                        @if($highlight->image_url)
                            <img src="{{ $highlight->image_url }}" alt="{{ $highlight->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                        <div class="absolute bottom-3 left-3 right-3">
                            <h3 class="font-bold text-base drop-shadow line-clamp-2" style="color: #fff !important;">{{ $highlight->title }}</h3>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Tours --}}
    @if($city->tours->isNotEmpty())
    <section class="mb-14">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400 mb-1">Curated experiences</p>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Tours in {{ $city->name }}</h2>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-2 sm:hidden">
                    <button type="button" class="city-tours-prev w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-brand-navy hover:border-blue-300 transition-colors">
                        <i class="fa-solid fa-arrow-left text-sm"></i>
                    </button>
                    <button type="button" class="city-tours-next w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-brand-navy hover:border-blue-300 transition-colors">
                        <i class="fa-solid fa-arrow-right text-sm"></i>
                    </button>
                </div>
                <a href="{{ route('tours.index', ['city' => $city->slug]) }}" class="text-sm font-medium text-brand-navy hover:text-brand-navy transition hidden sm:block">
                    View all tours &rarr;
                </a>
            </div>
        </div>
        {{-- Grid on desktop --}}
        <div class="hidden sm:grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($city->tours->where('is_active', true)->take(8) as $tour)
                <x-tour-card :tour="$tour" :queryParams="[]" />
            @endforeach
        </div>
        {{-- Slider on mobile --}}
        <div class="swiper city-tours-swiper overflow-visible block sm:!hidden">
            <div class="swiper-wrapper">
                @foreach($city->tours->where('is_active', true)->take(8) as $tour)
                <div class="swiper-slide">
                    <x-tour-card :tour="$tour" :queryParams="['city' => $city->slug]" :slider="true" />
                </div>
                @endforeach
            </div>
        </div>
        <div class="mt-6 text-center sm:hidden">
            <a href="{{ route('tours.index', ['city' => $city->slug]) }}" class="text-sm font-medium text-brand-navy hover:text-brand-navy transition">
                View all tours &rarr;
            </a>
        </div>
    </section>
    @endif

    {{-- Hotels --}}
    @if($city->hotels->isNotEmpty())
    <section>
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400 mb-1">Where to stay</p>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Hotels in {{ $city->name }}</h2>
            </div>
            <div class="flex items-center gap-2 sm:hidden">
                <button type="button" class="city-hotels-prev w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-brand-navy hover:border-blue-300 transition-colors">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </button>
                <button type="button" class="city-hotels-next w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-brand-navy hover:border-blue-300 transition-colors">
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </button>
            </div>
        </div>
        {{-- Grid on desktop (initial 8, load 8 more) --}}
        <div class="hidden sm:block" x-data="{ displayed: 8 }">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($city->hotels as $hotel)
                <a href="{{ route('hotels.show', $hotel->slug) }}" x-show="{{ $loop->index }} < displayed" x-cloak
                    class="group block bg-white rounded-xl overflow-hidden border border-gray-100 hover:border-blue-300 hover:shadow-lg transition-all duration-300">
                    <div class="aspect-[4/3] bg-gray-200 overflow-hidden">
                        @if($hotel->image_url)
                            <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">No image</div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 group-hover:text-brand-navy transition-colors">{{ $hotel->name }}</h3>
                        <div class="mt-1 flex items-center gap-2 text-sm">
                            @if($hotel->stars_rating)
                                <span class="flex items-center gap-0.5 text-amber-400">
                                    @for($i = 1; $i <= $hotel->stars_rating; $i++)
                                        <i class="fa-solid fa-star text-xs"></i>
                                    @endfor
                                </span>
                            @endif
                            <span class="text-gray-400">{{ $hotel->total_reviews > 0 ? $hotel->total_reviews . ' ' . Str::plural('review', $hotel->total_reviews) : 'No reviews' }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div x-show="displayed < {{ $city->hotels->count() }}" x-cloak class="mt-8 text-center">
                <button type="button" @click="displayed += 8"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-white border-2 border-brand-navy text-brand-navy font-semibold rounded-xl hover:bg-blue-50 transition-colors">
                    Load more hotels
                    <i class="fa-solid fa-chevron-down text-sm"></i>
                </button>
            </div>
        </div>
        {{-- Slider on mobile --}}
        <div class="swiper city-hotels-swiper overflow-visible block sm:!hidden">
            <div class="swiper-wrapper">
                @foreach($city->hotels as $hotel)
                <div class="swiper-slide">
                    <a href="{{ route('hotels.show', $hotel->slug) }}" class="group block bg-white rounded-xl overflow-hidden border border-gray-100 hover:border-blue-300 hover:shadow-lg transition-all duration-300">
                        <div class="aspect-[4/3] bg-gray-200 overflow-hidden">
                            @if($hotel->image_url)
                                <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">No image</div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 group-hover:text-brand-navy transition-colors">{{ $hotel->name }}</h3>
                            <div class="mt-1 flex items-center gap-2 text-sm">
                                @if($hotel->stars_rating)
                                    <span class="flex items-center gap-0.5 text-amber-400">
                                        @for($i = 1; $i <= $hotel->stars_rating; $i++)
                                            <i class="fa-solid fa-star text-xs"></i>
                                        @endfor
                                    </span>
                                @endif
                                <span class="text-gray-400">{{ $hotel->total_reviews > 0 ? $hotel->total_reviews . ' ' . Str::plural('review', $hotel->total_reviews) : 'No reviews' }}</span>
                            </div>
                            @if($hotel->location)
                                <p class="text-sm text-gray-500 mt-1.5 line-clamp-1">{{ $hotel->location }}</p>
                            @endif
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
@vite(['resources/js/tour-gallery.js'])

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.Swiper && document.querySelector('.city-highlights-swiper')) {
        new window.Swiper('.city-highlights-swiper', {
            modules: [window.SwiperNavigation],
            slidesPerView: 2,
            spaceBetween: 20,
            navigation: {
                prevEl: '.city-highlights-prev',
                nextEl: '.city-highlights-next',
            },
            breakpoints: {
                640: { slidesPerView: 2, spaceBetween: 20 },
                1024: { slidesPerView: 4, spaceBetween: 20 },
            },
        });
    }
    if (window.Swiper && document.querySelector('.city-tours-swiper')) {
        new window.Swiper('.city-tours-swiper', {
            modules: [window.SwiperNavigation],
            slidesPerView: 1.2,
            spaceBetween: 20,
            navigation: {
                prevEl: '.city-tours-prev',
                nextEl: '.city-tours-next',
            },
        });
    }
    if (window.Swiper && document.querySelector('.city-hotels-swiper')) {
        new window.Swiper('.city-hotels-swiper', {
            modules: [window.SwiperNavigation],
            slidesPerView: 1.2,
            spaceBetween: 20,
            navigation: {
                prevEl: '.city-hotels-prev',
                nextEl: '.city-hotels-next',
            },
        });
    }
});
</script>
@endpush
@endsection
