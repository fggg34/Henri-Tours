@extends('layouts.site')

@section('title', ($highlight->translate('title') ?? $highlight->title) . ' - ' . ($city->translate('name') ?? $city->name) . ' - ' . config('app.name'))
@section('description', Str::limit(strip_tags($highlight->translate('description') ?? $highlight->description), 160))

@push('meta')
<meta property="og:title" content="{{ $highlight->translate('title') ?? $highlight->title }} - {{ $city->translate('name') ?? $city->name }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($highlight->translate('description') ?? $highlight->description), 200) }}">
<meta property="og:url" content="{{ request()->url() }}">
@if($highlight->image_url)
<meta property="og:image" content="{{ request()->getSchemeAndHttpHost() . $highlight->image_url }}">
@endif
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-500 mb-8" aria-label="Breadcrumb">
        <ol class="flex items-center gap-1.5 flex-wrap">
            <li><a href="{{ localized_route('home') }}" class="text-brand-navy hover:text-brand-navy transition">Home</a></li>
            <li>/</li>
            <li><a href="{{ localized_route('cities.show', ['slug' => $city->slug]) }}" class="text-brand-navy hover:text-brand-navy transition">{{ $city->translate('name') ?? $city->name }}</a></li>
            <li>/</li>
            <li class="text-gray-700">{{ $highlight->translate('title') ?? $highlight->title }}</li>
        </ol>
    </nav>

    {{-- Hero: Image left + Title/Description right --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 mb-14">
        {{-- Left: Image --}}
        <div>
            @if($highlight->image_url)
                <div class="rounded-2xl overflow-hidden bg-gray-200 h-full" style="min-height: 400px;">
                    <img src="{{ $highlight->image_url }}" alt="{{ $highlight->translate('title') ?? $highlight->title }}" class="w-full h-full object-cover">
                </div>
            @else
                <div class="rounded-2xl bg-gray-100 h-full flex items-center justify-center text-gray-400" style="min-height: 400px;">
                    No image available
                </div>
            @endif
        </div>

        {{-- Right: Title + Description --}}
        <div class="flex flex-col justify-center">
            <div class="flex items-center gap-2 mb-3">
                <a href="{{ localized_route('cities.show', ['slug' => $city->slug]) }}" class="inline-flex items-center gap-1.5 text-xs font-medium uppercase tracking-wider text-brand-navy hover:text-brand-navy transition">
                    <i class="fa-solid fa-location-dot text-[10px]"></i>
                    {{ $city->translate('name') ?? $city->name }}, {{ $city->country }}
                </a>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-5">{{ $highlight->translate('title') ?? $highlight->title }}</h1>
            @if($highlight->translate('description') ?? $highlight->description)
                <div class="prose prose-gray max-w-none text-gray-600">
                    {!! $highlight->translate('description') ?? $highlight->description !!}
                </div>
            @else
                <p class="text-gray-500">No description available yet.</p>
            @endif
            <div class="mt-6 pt-6 border-t border-gray-100">
                <a href="{{ localized_route('cities.show', ['slug' => $city->slug]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-btn hover:bg-brand-btn-hover text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    Explore {{ $city->translate('name') ?? $city->name }}
                </a>
            </div>
        </div>
    </div>

    {{-- Related highlights from the same city --}}
    @if($otherHighlights->isNotEmpty())
    <section class="pt-10 border-t border-gray-200 overflow-hidden">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400 mb-1">Keep exploring</p>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">More places to visit in {{ $city->translate('name') ?? $city->name }}</h2>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" class="highlight-more-prev w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-brand-navy hover:border-blue-300 transition-colors">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </button>
                <button type="button" class="highlight-more-next w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-brand-navy hover:border-blue-300 transition-colors">
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </button>
            </div>
        </div>
        <div class="swiper highlight-more-swiper overflow-visible">
            <div class="swiper-wrapper">
                @foreach($otherHighlights as $other)
                <div class="swiper-slide">
                    <a href="{{ localized_route('cities.highlights.show', ['city' => $city->slug, 'highlight' => $other->slug]) }}" class="group block relative rounded-xl overflow-hidden bg-gray-200" style="aspect-ratio: 4/3;">
                        @if($other->image_url)
                            <img src="{{ $other->image_url }}" alt="{{ $other->translate('title') ?? $other->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                        <div class="absolute bottom-3 left-3 right-3">
                            <h3 class="font-bold text-base drop-shadow line-clamp-2" style="color: #fff !important;">{{ $other->translate('title') ?? $other->title }}</h3>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.Swiper && document.querySelector('.highlight-more-swiper')) {
        new window.Swiper('.highlight-more-swiper', {
            modules: [window.SwiperNavigation],
            slidesPerView: 2,
            spaceBetween: 20,
            navigation: {
                prevEl: '.highlight-more-prev',
                nextEl: '.highlight-more-next',
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
