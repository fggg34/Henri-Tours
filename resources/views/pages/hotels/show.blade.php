@extends('layouts.site')

@section('title', $hotel->name . ' - ' . config('app.name'))
@section('description', Str::limit(strip_tags($hotel->description), 160))

@push('meta')
<meta property="og:title" content="{{ $hotel->name }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($hotel->description), 200) }}">
<meta property="og:url" content="{{ request()->url() }}">
@if($hotel->image_url)
<meta property="og:image" content="{{ request()->getSchemeAndHttpHost() . $hotel->image_url }}">
@endif
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-500 mb-5" aria-label="Breadcrumb">
        <ol class="flex items-center gap-1.5 flex-wrap">
            <li><a href="{{ route('home') }}" class="text-brand-navy hover:text-brand-navy transition">Home</a></li>
            <li>/</li>
            @if($hotel->city)
                <li><a href="{{ route('cities.show', $hotel->city->slug) }}" class="text-brand-navy hover:text-brand-navy transition">{{ $hotel->city->name }}</a></li>
                <li>/</li>
            @endif
            <li class="text-gray-700">{{ $hotel->name }}</li>
        </ol>
    </nav>

    {{-- Name + rating + reviews --}}
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $hotel->name }}</h1>
        <div class="mt-2 flex flex-wrap items-center gap-3 text-gray-600">
            @if($hotel->stars_rating)
                <span class="flex items-center gap-0.5 text-amber-400" aria-label="{{ $hotel->stars_rating }} stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-solid fa-star text-sm {{ $i <= $hotel->stars_rating ? 'text-amber-400' : 'text-gray-200' }}"></i>
                    @endfor
                </span>
            @endif
            <span class="text-sm text-gray-500">
                @if($hotel->total_reviews > 0)
                    {{ $hotel->total_reviews }} {{ Str::plural('review', $hotel->total_reviews) }}
                @else
                    No reviews yet
                @endif
            </span>
            @if($hotel->city)
                <span class="text-gray-300">·</span>
                <a href="{{ route('cities.show', $hotel->city->slug) }}" class="text-sm text-brand-navy hover:text-brand-navy transition">{{ $hotel->city->name }}, {{ $hotel->city->country }}</a>
            @endif
        </div>
    </div>

    {{-- Gallery --}}
    @php
        $allImages = $hotel->getAllImageUrls();
        $hasGallery = count($allImages) > 0;
        $gridImages = array_slice($allImages, 1, 4);
    @endphp
    @if($hasGallery)
    <div class="mb-10 hotel-gallery">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 overflow-hidden rounded-2xl bg-gray-200" style="height: 480px;">
            <a href="{{ $allImages[0] }}" class="glightbox block relative overflow-hidden h-full" data-gallery="hotel-gallery-{{ $hotel->id }}">
                <img src="{{ $allImages[0] }}" alt="{{ $hotel->name }}" class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition-transform duration-500">
            </a>
            <div class="hotel-gallery grid grid-cols-2 grid-rows-2 gap-2 h-full">
                @for($i = 0; $i < 4; $i++)
                    @if(isset($gridImages[$i]))
                        <a href="{{ $gridImages[$i] }}" class="glightbox block relative overflow-hidden bg-gray-300" data-gallery="hotel-gallery-{{ $hotel->id }}">
                            <img src="{{ $gridImages[$i] }}" alt="{{ $hotel->name }} - {{ $i + 2 }}" class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                        </a>
                    @else
                        <div class="relative overflow-hidden bg-gray-200"></div>
                    @endif
                @endfor
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2 space-y-10">

            {{-- About --}}
            @if($hotel->description)
            <section>
                <h2 class="text-xl font-bold text-gray-900 mb-4">About this hotel</h2>
                <div class="prose prose-gray max-w-none text-gray-600">
                    {!! $hotel->description !!}
                </div>
            </section>
            @endif

            {{-- Map --}}
            @if($hotel->map_lat && $hotel->map_lng || $hotel->location)
            <section>
                <h2 class="text-xl font-bold text-gray-900 mb-4">Location</h2>
                <div class="rounded-2xl overflow-hidden border border-gray-200 bg-gray-50">
                    @if($hotel->map_lat && $hotel->map_lng)
                    <div class="aspect-video w-full relative">
                        <iframe
                            title="Map showing hotel location"
                            src="https://www.google.com/maps?q={{ urlencode((string)$hotel->map_lat . ',' . (string)$hotel->map_lng) }}&z=15&output=embed"
                            class="absolute inset-0 w-full h-full border-0"
                            allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    @endif
                    <div class="p-4 flex flex-wrap items-center justify-between gap-3">
                        @if($hotel->location)
                            <p class="text-sm text-gray-600"><i class="fa-solid fa-location-dot text-gray-400 mr-1.5"></i>{{ $hotel->location }}</p>
                        @endif
                        @if($hotel->google_maps_url)
                            <a href="{{ $hotel->google_maps_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white bg-brand-btn hover:bg-brand-btn-hover transition">
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                Open in Google Maps
                            </a>
                        @endif
                    </div>
                </div>
            </section>
            @endif

            {{-- Amenities --}}
            @if($hotel->amenities->isNotEmpty())
            <section>
                <h2 class="text-xl font-bold text-gray-900 mb-4">Hotel facilities</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($hotel->amenities as $amenity)
                        <div class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-100 bg-white">
                            @if($amenity->icon)
                                <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-brand-navy flex-shrink-0"><i class="{{ $amenity->icon }} text-base"></i></span>
                            @else
                                <span class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-check text-gray-500 text-xs"></i>
                                </span>
                            @endif
                            <span class="text-gray-700 text-sm font-medium">{{ $amenity->name }}</span>
                        </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- House rules --}}
            @if($hotel->house_rules && count($hotel->house_rules) > 0)
            <section>
                <h2 class="text-xl font-bold text-gray-900 mb-4">House rules</h2>
                <div class="bg-white rounded-xl border border-gray-100 divide-y divide-gray-100">
                    @foreach($hotel->house_rules as $rule)
                        @if(!empty($rule['label']) || !empty($rule['value']))
                            <div class="flex items-center justify-between gap-4 px-5 py-3.5">
                                <span class="font-medium text-gray-700 text-sm">{{ $rule['label'] ?? '—' }}</span>
                                <span class="text-gray-500 text-sm">{{ $rule['value'] ?? '—' }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </section>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-6">
                <!-- @if($hotel->phone || $hotel->email || $hotel->website || $hotel->location || $hotel->google_maps_url)
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Contact details</h2>
                    <div class="space-y-4">
                        @if($hotel->phone)
                        <a href="tel:{{ $hotel->phone }}" class="flex items-center gap-3 text-sm text-gray-600 hover:text-brand-navy transition group">
                            <span class="w-9 h-9 rounded-lg bg-gray-50 group-hover:bg-blue-50 flex items-center justify-center flex-shrink-0 transition">
                                <i class="fa-solid fa-phone text-gray-400 group-hover:text-brand-navy transition"></i>
                            </span>
                            {{ $hotel->phone }}
                        </a>
                        @endif
                        @if($hotel->email)
                        <a href="mailto:{{ $hotel->email }}" class="flex items-center gap-3 text-sm text-gray-600 hover:text-brand-navy transition group">
                            <span class="w-9 h-9 rounded-lg bg-gray-50 group-hover:bg-blue-50 flex items-center justify-center flex-shrink-0 transition">
                                <i class="fa-solid fa-envelope text-gray-400 group-hover:text-brand-navy transition"></i>
                            </span>
                            <span class="break-all">{{ $hotel->email }}</span>
                        </a>
                        @endif
                        @if($hotel->website)
                        <a href="{{ $hotel->website }}" target="_blank" rel="noopener" class="flex items-center gap-3 text-sm text-gray-600 hover:text-brand-navy transition group">
                            <span class="w-9 h-9 rounded-lg bg-gray-50 group-hover:bg-blue-50 flex items-center justify-center flex-shrink-0 transition">
                                <i class="fa-solid fa-globe text-gray-400 group-hover:text-brand-navy transition"></i>
                            </span>
                            <span class="break-all">{{ Str::limit($hotel->website, 35) }}</span>
                        </a>
                        @endif
                        @if($hotel->google_maps_url)
                        <a href="{{ $hotel->google_maps_url }}" target="_blank" rel="noopener" class="flex items-center gap-3 text-sm font-medium text-brand-navy hover:text-brand-navy transition group">
                            <span class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-map-location-dot text-brand-navy"></i>
                            </span>
                            Open in Google Maps
                        </a>
                        @endif
                    </div>
                </div>
                @endif -->

                @if($hotel->city)
                <div class="rounded-2xl border border-gray-100 bg-white overflow-hidden shadow-sm">
                    <a href="{{ route('cities.show', $hotel->city->slug) }}" class="group block p-5 text-center hover:bg-gray-50/50 transition">
                        <i class="fa-solid fa-map-location-dot text-2xl text-gray-300 group-hover:text-brand-navy transition mb-2"></i>
                        <span class="text-sm text-gray-500 block">Explore</span>
                        <span class="font-bold text-gray-900 text-lg block group-hover:text-brand-navy transition">{{ $hotel->city->name }}</span>
                    </a>
                    <div class="flex flex-wrap items-center justify-center gap-4 px-5 pb-5 pt-5 border-t border-gray-100">
                        @if($hotel->city->tours->count())
                            <a href="{{ route('cities.show', $hotel->city->slug) }}" class="flex items-center gap-2 text-sm hover:text-brand-navy transition">
                                <span class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-route text-brand-navy text-xs"></i></span>
                                <span class="font-semibold text-gray-900">{{ $hotel->city->tours->where('is_active', true)->count() }}</span>
                                <span class="text-gray-500">Tours</span>
                            </a>
                        @endif
                        @if($hotel->city->hotels->count())
                            <a href="{{ route('cities.show', $hotel->city->slug) }}" class="flex items-center gap-2 text-sm hover:text-brand-navy transition">
                                <span class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-hotel text-brand-navy text-xs"></i></span>
                                <span class="font-semibold text-gray-900">{{ $hotel->city->hotels->count() }}</span>
                                <span class="text-gray-500">Hotels</span>
                            </a>
                        @endif
                        @if($hotel->city->highlights->count())
                            <a href="{{ route('cities.show', $hotel->city->slug) }}" class="flex items-center gap-2 text-sm hover:text-brand-navy transition">
                                <span class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-camera text-brand-navy text-xs"></i></span>
                                <span class="font-semibold text-gray-900">{{ $hotel->city->highlights->count() }}</span>
                                <span class="text-gray-500">Attractions</span>
                            </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Related hotels --}}
    @if(isset($otherHotels) && $otherHotels->isNotEmpty())
    <section class="mt-16 pt-10 border-t border-gray-200">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400 mb-1">Other places to stay</p>
                <h2 class="text-2xl font-bold text-gray-900">
                    @if($hotel->city)
                        Related hotels in {{ $hotel->city->name }}
                    @else
                        Related hotels
                    @endif
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" class="related-hotels-prev w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-brand-navy hover:border-blue-300 transition-colors">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </button>
                <button type="button" class="related-hotels-next w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-brand-navy hover:border-blue-300 transition-colors">
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </button>
            </div>
        </div>
        <div class="swiper related-hotels-swiper overflow-visible md:overflow-hidden">
            <div class="swiper-wrapper">
                @foreach($otherHotels as $other)
                <div class="swiper-slide">
                    <a href="{{ route('hotels.show', $other->slug) }}" class="group block bg-white rounded-xl overflow-hidden border border-gray-100 hover:border-blue-300 hover:shadow-lg transition-all duration-300">
                        <div class="aspect-[4/3] bg-gray-200 overflow-hidden">
                            @if($other->image_url)
                                <img src="{{ $other->image_url }}" alt="{{ $other->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">No image</div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 group-hover:text-brand-navy transition">{{ $other->name }}</h3>
                            
                            <div class="mt-2 flex items-center gap-3 text-sm">
                                @if($other->stars_rating)
                                    <span class="flex items-center gap-0.5 text-amber-400">
                                        @for($i = 1; $i <= $other->stars_rating; $i++)
                                            <i class="fa-solid fa-star text-xs"></i>
                                        @endfor
                                    </span>
                                @endif
                                <span class="text-gray-400">
                                    {{ $other->total_reviews > 0 ? $other->total_reviews . ' ' . Str::plural('review', $other->total_reviews) : 'No reviews' }}
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @elseif($hotel->city)
    <section class="mt-16 pt-10 border-t border-gray-200 text-center">
        <p class="text-gray-500 text-sm">No other hotels in this city yet.</p>
        <a href="{{ route('cities.show', $hotel->city->slug) }}" class="inline-block mt-2 text-sm font-medium text-brand-navy hover:text-brand-navy transition">Explore {{ $hotel->city->name }} &rarr;</a>
    </section>
    @endif
</div>
@endsection

@push('scripts')
@vite(['resources/js/tour-gallery.js'])
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.Swiper && document.querySelector('.related-hotels-swiper')) {
        new window.Swiper('.related-hotels-swiper', {
            modules: [window.SwiperNavigation],
            slidesPerView: 1.2,
            spaceBetween: 20,
            navigation: {
                prevEl: '.related-hotels-prev',
                nextEl: '.related-hotels-next',
            },
            breakpoints: {
                640: { slidesPerView: 2, spaceBetween: 20 },
                1024: { slidesPerView: 4, spaceBetween: 20 },
            },
        });
    }
});
</script>
@endpush
