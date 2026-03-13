@extends('layouts.site')

@section('title', $tour->translate('meta_title') ?: $tour->translate('title') . ' - ' . config('app.name'))
@section('description', $tour->translate('meta_description') ?: Str::limit($tour->translate('short_description'), 160))

@push('meta')
@if($tour->translate('meta_title'))<meta property="og:title" content="{{ $tour->translate('meta_title') }}">@endif
<meta property="og:description" content="{{ $tour->translate('meta_description') ?: $tour->translate('short_description') }}">
<meta property="og:url" content="{{ request()->url() }}">
@endpush

@push('styles')
<style>
.prose ul {
    list-style: disc;
    padding-left: 17px;
}
.prose ul li {
    margin-bottom: 10px;
}
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @php
        $images = $tour->images->isEmpty() ? collect([null]) : $tour->images;
        $firstImage = $images->first();
        $mainImageUrl = $firstImage && $firstImage->path ? $firstImage->url : 'https://placehold.co/1200x600?text=Tour';
    @endphp
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-12">
            <div class="flex flex-col items-left">
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $tour->translate('title') }}</h1>
                    <!-- @if($tour->season)
                        @php
                            $seasonLabels = ['summer' => 'Summer', 'winter' => 'Winter', 'all_season' => 'All Season'];
                            $seasonStyles = ['summer' => 'bg-amber-100 text-amber-800', 'winter' => 'bg-sky-100 text-sky-800', 'all_season' => 'bg-emerald-100 text-emerald-800'];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold {{ $seasonStyles[$tour->season] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $seasonLabels[$tour->season] ?? $tour->season }} Tour
                        </span>
                    @endif -->
                </div>
                <!-- <p class="mt-2 text-gray-600">{{ $tour->short_description }}</p> -->
                @if($tour->approvedReviews->count() > 0)
                    <p class="mt-2 flex items-center gap-2">
                        <x-review-stars :rating="(float) $tour->average_rating" />
                        <span class="text-sm text-gray-500">({{ $tour->approvedReviews->count() }} reviews)</span>
                    </p>
                @endif
            </div>
            @php
                $galleryImages = $tour->images->isEmpty() ? collect([(object)['url' => $mainImageUrl, 'alt' => $tour->translate('title')]]) : $tour->images;
                $img1 = $galleryImages->get(0);
                $img2 = $galleryImages->get(1);
                $img3 = $galleryImages->get(2);
                $img4 = $galleryImages->get(3);
                $totalImages = $galleryImages->count();
            @endphp
            <div class="tour-gallery mt-5" style="margin-top: 20px;">
            {{--
              Parent grid: 2 cols. Left = 1 large (row span 2). Right = nested grid.
              <640px: 1 col. 640-1023px: 2 cols. >=1024px: full layout with nested right.
            --}}
            <div class="tour-gallery-grid grid grid-cols-1 sm:grid-cols-2 gap-4 rounded-xl overflow-hidden">
                {{-- 1. Left: large image, full width on mobile, spans full height on lg --}}
                <a href="{{ $img1->url ?? $mainImageUrl }}" class="glightbox block overflow-hidden rounded-lg min-h-[220px] sm:min-h-[200px] lg:row-span-2" data-gallery="tour-gallery-{{ $tour->id }}" role="listitem">
                    <img src="{{ $img1->url ?? $mainImageUrl }}" alt="{{ $img1->alt ?? $tour->translate('title') }}" class="w-full h-full min-h-[220px] sm:min-h-[200px] object-cover cursor-pointer hover:opacity-95 transition-opacity" loading="eager" fetchpriority="high">
                </a>
                {{-- Right column: on mobile = 2 images 50/50 (130px). On sm+ = nested grid --}}
                <div class="tour-gallery-right grid grid-cols-2 grid-rows-[130px] sm:grid-cols-2 sm:grid-rows-2 sm:min-h-0 gap-4">
                @if($img2)
                    <a href="{{ $img2->url }}" class="glightbox block overflow-hidden rounded-lg h-[130px] sm:h-auto sm:min-h-0" data-gallery="tour-gallery-{{ $tour->id }}" role="listitem">
                        <img src="{{ $img2->url }}" alt="{{ $img2->alt ?? 'Tour image' }}" class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition-opacity" loading="lazy">
                    </a>
                @endif
                @if($img3)
                    <div class="relative overflow-hidden rounded-lg h-[130px] sm:h-auto sm:min-h-0">
                        <a href="{{ $img3->url }}" class="glightbox block w-full h-full overflow-hidden" data-gallery="tour-gallery-{{ $tour->id }}" role="listitem">
                            <img src="{{ $img3->url }}" alt="{{ $img3->alt ?? 'Tour image' }}" class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition-opacity" loading="lazy">
                        </a>
                        {{-- View all: mobile only, bottom right of third image --}}
                        @if($totalImages > 4)
                            <a href="{{ $img1->url ?? $mainImageUrl }}" class="glightbox sm:hidden absolute bottom-2 right-2 flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-black/60 text-white text-xs font-medium hover:bg-black/75 transition cursor-pointer z-10" data-gallery="tour-gallery-{{ $tour->id }}" aria-label="View all {{ $totalImages }} photos">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                                View all
                            </a>
                        @endif
                    </div>
                @endif
                @if($img4)
                    <div class="hidden sm:block lg:col-span-2 relative overflow-hidden rounded-lg sm:min-h-0">
                        <a href="{{ $img4->url }}" class="glightbox block w-full h-full overflow-hidden" data-gallery="tour-gallery-{{ $tour->id }}" role="listitem">
                            <img src="{{ $img4->url }}" alt="{{ $img4->alt ?? 'Tour image' }}" class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition-opacity" loading="lazy">
                        </a>
                        {{-- View all: sm+ only, bottom right of fourth image --}}
                        @if($totalImages > 4)
                            <a style="z-index: 0;" href="{{ $img1->url ?? $mainImageUrl }}" class="glightbox hidden sm:flex absolute bottom-4 right-4 items-center gap-1.5 px-3 py-2 rounded-lg bg-black/60 text-white text-sm font-medium hover:bg-black/75 transition cursor-pointer z-10" data-gallery="tour-gallery-{{ $tour->id }}" aria-label="View all {{ $totalImages }} photos">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                                View all
                            </a>
                        @endif
                    </div>
                @endif
                </div>
            </div>
            {{-- Hidden links for images 5+ so they appear in the lightbox --}}
            @if($totalImages > 4)
                <div class="hidden">
                    @foreach($galleryImages->skip(4) as $extraImg)
                        <a href="{{ $extraImg->url }}" class="glightbox" data-gallery="tour-gallery-{{ $tour->id }}">
                            <img src="{{ $extraImg->url }}" alt="{{ $extraImg->alt ?? 'Tour image' }}" loading="lazy">
                        </a>
                    @endforeach
                </div>
            @endif
            </div>

            @php
                $durationLabel = $tour->duration_days
                    ? $tour->duration_days . ' day' . ($tour->duration_days > 1 ? 's' : '')
                    : ($tour->duration_hours ? $tour->duration_hours . ' hours' : null);
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-2" style="margin-top: 20px;">
                <div class="flex gap-2">
                    <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                        <i class="fa-solid fa-flag text-slate-600 text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-gray-600">Tour starts</p>
                        <p class="text-sm text-sky-600 truncate" title="{{ $tour->translate('start_location') }}">{{ $tour->translate('start_location') ?: '—' }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                        <i class="fa-solid fa-sun text-slate-600 text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-gray-600">Duration</p>
                        <p class="text-sm text-sky-600">{{ $durationLabel ?: '—' }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                        <i class="fa-solid fa-suitcase text-slate-600 text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-gray-600">Ending place</p>
                        <p class="text-sm text-sky-600 truncate" title="{{ $tour->translate('end_location') ?? '' }}">{{ $tour->translate('end_location') ?? ($tour->translate('start_location') ?: '—') }}</p>
                    </div>
                </div>
                @if($tour->category)
                <div class="flex gap-2">
                    <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                        <i class="fa-solid fa-route text-slate-600 text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-gray-600">Tour Type</p>
                        <p class="text-sm text-sky-600">{{ $tour->category?->translate('name') ?? $tour->category?->name }}</p>
                    </div>
                </div>
                @endif
                @if($tour->difficulty)
                @php
                    $difficultyLabels = ['easy' => 'Easy', 'moderate' => 'Moderate', 'challenging' => 'Challenging', 'strenuous' => 'Strenuous'];
                @endphp
                <div class="flex gap-2">
                    <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                        <i class="fa-solid fa-mountain text-slate-600 text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-gray-600">Difficulty</p>
                        <p class="text-sm text-sky-600">{{ $difficultyLabels[$tour->difficulty] ?? $tour->difficulty }}</p>
                    </div>
                </div>
                @endif
                @if($tour->season)
                @php
                    $seasonLabels = ['summer' => 'Summer', 'winter' => 'Winter', 'all_season' => 'All Season'];
                @endphp
                <div class="flex gap-2">
                    <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                        <i class="fa-solid fa-calendar-check text-slate-600 text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-gray-600">Availability</p>
                        <p class="text-sm text-sky-600">{{ $seasonLabels[$tour->season] ?? $tour->season }}</p>
                    </div>
                </div>
                @endif
                @if($tour->max_group_size)
                <div class="flex gap-2">
                    <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                        <i class="fa-solid fa-user-group text-slate-600 text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-gray-600">Max people</p>
                        <p class="text-sm text-sky-600">{{ $tour->max_group_size }}</p>
                    </div>
                </div>
                @endif
                @php $langs = $tour->translate('languages'); @endphp
                @if($langs && count((array) $langs) > 0)
                <div class="flex gap-2">
                    <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                        <i class="fa-solid fa-language text-slate-600 text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-gray-600">Language</p>
                        <p class="text-sm text-sky-600">{{ implode(', ', (array) $langs) }}</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="prose max-w-none">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Summary</h2>
                {!! $tour->translate('description') !!}
            </div>

            @php $tourHighlights = $tour->translate('tour_highlights'); @endphp
            @if(($tourHighlights && count((array) $tourHighlights) > 0) || $tour->activities->isNotEmpty())
            <div class="grid grid-cols-1 @if(($tourHighlights && count((array) $tourHighlights) > 0) && $tour->activities->isNotEmpty()) md:grid-cols-2 @endif gap-8">
                @if($tourHighlights && count((array) $tourHighlights) > 0)
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Tour highlights</h2>
                        <ul class="space-y-3">
                            @foreach((array) $tourHighlights as $highlight)
                                @php $text = is_array($highlight) ? ($highlight['text'] ?? $highlight['value'] ?? '') : $highlight; @endphp
                                @if($text)
                                    <li class="flex items-start gap-3">
                                        <span class="flex-shrink-0 w-7 h-7 rounded flex items-center justify-center bg-blue-100 text-brand-navy">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                        </span>
                                        <span class="text-gray-700">{{ $text }}</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if($tour->activities->isNotEmpty())
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Tour activities</h2>
                        <div class="flex flex-wrap gap-4">
                            @foreach($tour->activities->sortBy('sort_order') as $activity)
                                <div class="flex items-center gap-3 px-4 py-3 rounded-lg border border-gray-200 bg-white">
                                    <x-tour-activity-icon :activity="$activity" class="w-8 h-8" />
                                    <span class="text-gray-700 font-medium">{{ $activity->translate('title') ?? $activity->title }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            @endif

            @if($tour->translate('important_notes'))
                <div class="rounded-xl border border-amber-200 bg-amber-50/50 p-5">
                    <h2 class="text-xl font-bold text-amber-800 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        Important notes
                    </h2>
                    <div class="prose prose-sm max-w-none text-amber-900">
                        {!! $tour->translate('important_notes') !!}
                    </div>
                </div>
            @endif

            @if($tour->itineraries->isNotEmpty())
                @php $firstId = $tour->itineraries->first()->id; @endphp
                <div class="" x-data="{ openDay: {{ $firstId }} }">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Itinerary &amp; Details</h2>
                    <div class="border border-gray-200 rounded-lg overflow-hidden divide-y divide-gray-200">
                        @foreach($tour->itineraries as $day)
                            <div class="bg-white">
                                <button type="button"
                                    @click="openDay = openDay === {{ $day->id }} ? null : {{ $day->id }}"
                                    :class="openDay === {{ $day->id }} ? 'bg-gray-100' : 'bg-white hover:bg-gray-50'"
                                    class="w-full flex items-center justify-between px-4 py-4 text-left font-semibold text-gray-900 transition-colors">
                                    <span>@if($day->day)Day {{ $day->day }}: @endif{{ $day->translate('title') }}</span>
                                    <span class="inline-flex w-7 h-7 flex-shrink-0 ml-2 items-center justify-center">
                                        <i class="fa-solid fa-chevron-down text-gray-500 text-base transition-transform duration-200"
                                            :class="openDay === {{ $day->id }} ? 'rotate-180' : ''"
                                            aria-hidden="true"></i>
                                    </span>
                                </button>
                                <div x-show="openDay === {{ $day->id }}"
                                    x-collapse
                                    class="border-t border-gray-200">
                                    <div class="px-4 py-4 text-gray-600 text-sm space-y-4">
                                        @if($day->translate('description'))
                                            <div class="prose prose-sm max-w-none text-gray-600">
                                                {!! $day->translate('description') !!}
                                            </div>
                                        @endif

                                        @if($day->highlights->isNotEmpty())
                                            <div class="pt-2">
                                                <p class="font-medium text-gray-700 mb-3">Places you'll visit:</p>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                    @foreach($day->highlights as $hl)
                                                        @php $city = $hl->cities->first(); @endphp
                                                        @if($city)
                                                            <a href="{{ route('cities.highlights.show', [$city->slug, $hl->slug]) }}" class="flex gap-3 p-3 rounded-lg border border-gray-200 bg-white hover:border-brand-navy hover:bg-blue-50/30 transition">
                                                                @if($hl->image_url)
                                                                    <img src="{{ $hl->image_url }}" alt="{{ $hl->title }}" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                                                                @else
                                                                    <div class="w-16 h-16 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                                                                        <i class="fa-solid fa-camera text-gray-400"></i>
                                                                    </div>
                                                                @endif
                                                                <div class="min-w-0 flex-1">
                                                                    <h4 class="font-semibold text-gray-900 text-sm">{{ $hl->title }}</h4>
                                                                    <p class="text-xs text-gray-500 truncate">{{ $city->name }}</p>
                                                                </div>
                                                                <i class="fa-solid fa-chevron-right text-gray-400 self-center"></i>
                                                            </a>
                                                        @else
                                                            <div class="flex gap-3 p-3 rounded-lg border border-gray-200 bg-gray-50/50">
                                                                @if($hl->image_url)
                                                                    <img src="{{ $hl->image_url }}" alt="{{ $hl->title }}" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                                                                @else
                                                                    <div class="w-16 h-16 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                                                                        <i class="fa-solid fa-camera text-gray-400"></i>
                                                                    </div>
                                                                @endif
                                                                <div class="min-w-0 flex-1">
                                                                    <h4 class="font-semibold text-gray-900 text-sm">{{ $hl->title }}</h4>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if($day->hotel)
                                            <div class="pt-2">
                                                <p class="font-medium text-gray-700 mb-3">Accommodation:</p>
                                                <a href="{{ route('hotels.show', $day->hotel->slug) }}" class="flex gap-4 p-4 rounded-lg border border-gray-200 bg-gray-50/50 hover:border-brand-navy hover:bg-blue-50/30 transition">
                                                    <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-gray-200">
                                                        @if($day->hotel->image_url)
                                                            <img src="{{ $day->hotel->image_url }}" alt="{{ $day->hotel->name }}" class="w-full h-full object-cover" loading="lazy">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No image</div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="font-semibold text-gray-900">{{ $day->hotel->name }}</h4>
                                                        <div class="mt-1 flex items-center gap-2">
                                                            @if($day->hotel->stars_rating)
                                                                <x-review-stars :rating="(float) $day->hotel->stars_rating" />
                                                                <span class="text-gray-500 text-xs">({{ $day->hotel->stars_rating }} stars)</span>
                                                            @endif
                                                        </div>
                                                        <!-- @if($day->hotel->location)
                                                            <p class="mt-1 text-gray-500 text-xs">{{ $day->hotel->location }}</p>
                                                        @endif -->
                                                        @if($day->hotel->total_reviews > 0)
                                                            <p class="mt-1 text-gray-500 text-xs">{{ $day->hotel->total_reviews }} {{ Str::plural('review', $day->hotel->total_reviews) }}</p>
                                                        @endif
                                                    </div>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- What to bring --}}
            @php $whatToBring = $tour->translate('what_to_bring'); @endphp
            @if($whatToBring && count((array) $whatToBring) > 0)
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-3">What to bring</h2>
                    <ul class="space-y-2">
                        @foreach((array) $whatToBring as $item)
                            <li class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-7 h-7 rounded flex items-center justify-center bg-sky-100 text-sky-600">
                                    <i class="fa-solid fa-suitcase text-sm"></i>
                                </span>
                                <span class="text-gray-700">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Included & Not included --}}
            @php $included = $tour->translate('included'); $notIncluded = $tour->translate('not_included'); @endphp
            @if($included || $notIncluded)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-12">
                    @if($included)
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-3">Included</h2>
                            <ul class="space-y-2">
                                @foreach((array) $included as $item)
                                    <li class="flex items-start gap-3">
                                        <span class="flex-shrink-0 w-7 h-7 rounded flex items-center justify-center bg-blue-100 text-brand-navy">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                        </span>
                                        <span class="text-gray-700">{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if($notIncluded)
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-3">Not included</h2>
                            <ul class="space-y-2">
                                @foreach((array) $notIncluded as $item)
                                    <li class="flex items-start gap-3">
                                        <span class="flex-shrink-0 w-7 h-7 rounded flex items-center justify-center bg-red-100 text-red-600">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </span>
                                        <span class="text-gray-700">{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Cancellation Policy & Questions --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mb-12">
                <div class="bg-white border border-gray-200 rounded-xl p-6 md:p-8 shadow-sm">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-4">Cancellation Policy</h2>
                    <p class="text-sm text-gray-600 leading-relaxed mb-6">For detailed Booking Terms and the Cancellation Policy, please read more</p>
                    <a href="{{ route('terms') }}" class="inline-flex items-center px-5 py-2 bg-white border border-gray-800 text-black font-medium rounded-md hover:bg-gray-50 transition-colors">
                        Show more
                    </a>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-6 md:p-8 shadow-sm">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-4">Questions?</h2>
                    <p class="text-sm text-gray-600 leading-relaxed mb-6">Feel free to contact us directly for any further questions via E-mail or WhatsApp.</p>
                    <a href="{{ route('contact') }}" class="inline-flex items-center px-5 py-2 bg-white border border-gray-800 text-black font-medium rounded-md hover:bg-gray-50 transition-colors">
                        Show more
                    </a>
                </div>
            </div>

            {{-- Customer reviews --}}
            @php
                $reviews = $tour->approvedReviews;
                $avgRating = (float) $tour->average_rating;
                $reviewCount = $tour->approvedReviews->count();
            @endphp
            <div x-data="{ visibleCount: 5 }">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6 mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">Customer reviews</h2>
                        <p class="mt-1 text-base text-gray-600">
                            Read what real customers had to say about <strong class="font-bold text-gray-900">{{ $tour->translate('title') }}.</strong>
                        </p>
                    </div>
                    @if($reviewCount > 0)
                    <div class="flex-shrink-0 w-full md:w-auto md:min-w-[200px]">
                        <div class="rounded-xl p-5 text-white bg-brand-navy">
                            <p class="text-sm">Overall rating for this trip</p>
                            @php
                                $fullStars = (int) floor($avgRating);
                                $halfStar = ($avgRating - $fullStars) >= 0.5;
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                            @endphp
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-4xl font-bold">{{ number_format($avgRating, 1) }}</span>
                                <span class="flex items-center -mt-1" aria-hidden="true">
                                    @for($i = 0; $i < $fullStars; $i++)
                                        <svg class="w-7 h-7 text-amber-400 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                    @if($halfStar)
                                        <svg class="w-7 h-7 text-amber-400 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><defs><linearGradient id="half-overall"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="rgba(255,255,255,0.4)"/></linearGradient></defs><path fill="url(#half-overall)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endif
                                    @for($i = 0; $i < $emptyStars; $i++)
                                        <svg class="w-7 h-7 text-white/40 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </span>
                            </div>
                            <p class="text-sm mt-1 text-white/80">based on {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Review cards: show 5 initially, then 5 more per click --}}
                <div class="space-y-5 mb-8">
                    @foreach($reviews as $index => $review)
                        @php
                            $name = $review->display_name;
                            $words = explode(' ', trim($name), 2);
                            $initials = count($words) >= 2
                                ? strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1))
                                : strtoupper(mb_substr($name, 0, 2));
                            if ($initials === '') { $initials = '?'; }
                            $displayDate = $review->display_date;
                        @endphp
                        <div x-show="{{ $index }} < visibleCount"
                             x-collapse
                             class="border border-gray-100 rounded-xl p-5 md:p-6 bg-white shadow-sm relative"
                             x-data="{ expanded: false }">
                            <x-review-platform-logo :platform="$review->platform" :url="$review->platform_tour_url" />
                            <div class="flex gap-4 {{ $review->platform ? 'pr-12' : '' }}">
                                <div class="w-12 h-12 rounded-full bg-gray-700 flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                                    {{ $initials }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-gray-900">
                                        {{ $name }}
                                        <span class="text-gray-500 font-normal"> {{ $displayDate->format('d/m/Y') }}</span>
                                    </p>
                                    <div class="mt-1.5 flex items-center gap-0.5" aria-label="Rating: {{ $review->rating }} out of 5">
                                        @for($i = 0; $i < min(5, (int) $review->rating); $i++)
                                            <i class="fa-solid fa-star text-amber-400 text-sm"></i>
                                        @endfor
                                        @for($i = (int) $review->rating; $i < 5; $i++)
                                            <i class="fa-regular fa-star text-gray-300 text-sm"></i>
                                        @endfor
                                    </div>
                                    @if($review->title)
                                        <h3 class="font-bold text-lg text-gray-900 mt-3">{{ $review->title }}</h3>
                                    @endif
                                    <div class="mt-2 text-gray-600">
                                        <p :class="expanded ? '' : 'line-clamp-4'">{{ $review->comment }}</p>
                                        @if(strlen($review->comment) > 280)
                                            <button type="button" @click="expanded = !expanded" class="text-brand-btn hover:text-brand-btn-hover hover:underline mt-1 text-sm font-medium" x-text="expanded ? 'Show less' : 'Show more'"></button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($reviewCount > 5)
                    <div class="flex justify-center mb-8">
                        <button type="button"
                                @click="visibleCount = Math.min(visibleCount + 5, {{ $reviewCount }})"
                                x-show="visibleCount < {{ $reviewCount }}"
                                x-transition
                                class="px-6 py-3 bg-brand-btn hover:bg-brand-btn-hover text-white font-medium rounded-lg transition-colors">
                            Show 5 more reviews
                        </button>
                    </div>
                @endif

                {{-- Leave a review form or login prompt --}}
                @auth
                    @if($userHasReviewed ?? false)
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 text-center">
                            <p class="text-gray-600">You have already reviewed this tour. Thank you!</p>
                        </div>
                    @else
                        <div class="rounded-xl border border-gray-200 bg-white p-6 md:p-8 shadow-sm">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Leave a review</h3>

                            @if(session('error'))
                                <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
                            @endif
                            @if(session('success'))
                                <div class="mb-6 p-4 bg-blue-50 text-blue-800 rounded-lg text-sm flex items-center gap-2">
                                    <i class="fa-solid fa-circle-check text-brand-navy"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('reviews.store', $tour) }}" method="POST" class="space-y-6" x-data="{ rating: {{ old('rating', 0) }} }" x-init="if(rating) $refs.ratingInput.value = rating">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Your overall rating <span class="text-red-500">*</span></label>
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button" @click="rating = {{ $i }}; $refs.ratingInput.value = {{ $i }}"
                                                class="p-1 focus:outline-none transition-colors">
                                                <i class="text-xl" :class="rating >= {{ $i }} ? 'fa-solid fa-star text-amber-400' : 'fa-regular fa-star text-gray-300'"></i>
                                            </button>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" x-ref="ratingInput" value="{{ old('rating', '') }}" required>
                                    @error('rating')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="review_title" class="block text-sm font-medium text-gray-700 mb-1.5">Title of your review <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" id="review_title" value="{{ old('title') }}" required
                                        placeholder="Summarize your review or highlight an interesting detail"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-navy focus:ring-blue-500">
                                    @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="review_comment" class="block text-sm font-medium text-gray-700 mb-1.5">Your review <span class="text-red-500">*</span></label>
                                    <textarea name="comment" id="review_comment" rows="5" required
                                        placeholder="Tell people your review"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-navy focus:ring-blue-500 resize-y">{{ old('comment') }}</textarea>
                                    @error('comment')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <button type="submit" class="px-6 py-3 bg-brand-btn hover:bg-brand-btn-hover text-white font-medium rounded-lg transition-colors">
                                    Submit Review
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="rounded-xl border border-gray-200 bg-white p-6 md:p-8 shadow-sm text-center">
                        <p class="text-gray-600 mb-5">To leave a review you need to log in.</p>
                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-brand-btn hover:bg-brand-btn-hover text-white font-medium rounded-lg transition-colors">
                            Log in to your account
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6 pt-0 lg:pt-[83px]">
            <!-- @auth
                @php $inWishlist = auth()->user()->wishlistTours()->where('tour_id', $tour->id)->exists(); @endphp
                @if($inWishlist)
                    <form action="{{ route('wishlist.destroy', $tour) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2 border border-gray-300 rounded-lg text-gray-700 text-sm font-medium hover:bg-gray-50">Remove from wishlist</button>
                    </form>
                @else
                    <form action="{{ route('wishlist.store', $tour) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-2 border border-brand-btn text-brand-btn rounded-lg text-sm font-medium hover:bg-brand-btn/10">Add to wishlist</button>
                    </form>
                @endif
            @endauth -->
            <div id="booking-form" class="lg:sticky lg:top-24 lg:self-start scroll-mt-12">
                <x-booking-sidebar :tour="$tour" :defaultDate="request('date')" :defaultGuests="request('adults', 2)" />
            </div>
        </div>
    </div>
</div>

<x-mobile-booking-bar :tour="$tour" />

@endsection

@push('scripts')
@vite(['resources/js/tour-gallery.js'])
@endpush
