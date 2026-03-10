@props(['tour', 'queryParams' => [], 'wishlisted' => false, 'slider' => false])

@php
    $firstImg = $tour->images->first();
    $imageUrl = $firstImg?->url ?? 'https://placehold.co/600x400/e2e8f0/64748b?text=Tour';
    $rating = $tour->average_rating ?? $tour->approvedReviews->avg('rating');
    $reviewCount = $tour->approvedReviews->count();
    $tourUrl = route('tours.show', $tour->slug);
    if (!empty($queryParams)) {
        $tourUrl .= '?' . http_build_query($queryParams);
    }

    $durationLabel = $tour->duration_days && $tour->duration_days > 1
        ? $tour->duration_days . ' Days'
        : ($tour->duration_hours
            ? $tour->duration_hours . ' Hours'
            : null);

    $categoryName = $tour->category?->name;
    $currency = ($tour->currency === 'EUR' || !$tour->currency) ? '€' : $tour->currency;
    $price = $tour->price ?? $tour->base_price ?? 0;
    $languages = is_array($tour->languages) ? implode(', ', $tour->languages) : $tour->languages;
@endphp

<article {{ $attributes->merge(['class' => 'group bg-white rounded-lg overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow ' . ($slider ? 'flex-shrink-0' : '')]) }}>
    {{-- Image --}}
    <a href="{{ $tourUrl }}" class="block relative overflow-hidden aspect-[4/3]">
        <img src="{{ $imageUrl }}" alt="{{ $tour->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
        @if($categoryName)
            <span class="absolute top-3 right-3 inline-flex items-center px-3 py-1 text-xs font-semibold rounded bg-brand-navy/90 text-white backdrop-blur-sm">{{ $categoryName }}</span>
        @endif
    </a>

    {{-- Content --}}
    <div class="p-4">
        {{-- Rating --}}
        <div class="flex items-center gap-1.5 mb-2">
            @if($rating)
                <div class="flex items-center gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($rating))
                            <i class="fa-solid fa-star text-amber-400 text-xs"></i>
                        @else
                            <i class="fa-regular fa-star text-gray-300 text-xs"></i>
                        @endif
                    @endfor
                </div>
                <span class="text-sm font-bold text-gray-800">{{ number_format($rating, 1) }}</span>
                <span class="text-sm text-gray-400">({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})</span>
            @endif
        </div>

        {{-- Title --}}
        <h3 class="text-base font-bold text-gray-900 leading-snug mb-3 line-clamp-2 min-h-[2.75rem]">
            <a href="{{ $tourUrl }}" class="hover:text-brand-navy transition-colors">{{ $tour->title }}</a>
        </h3>

        {{-- Info grid --}}
        <div class="tour-info-grid text-xs text-gray-500 mb-4">
            @if($tour->start_location)
                <div class="flex items-start gap-1.5">
                    <i class="fa-solid fa-location-dot text-brand-btn mt-0.5 flex-shrink-0"></i>
                    <div>
                        <span class="block font-medium text-gray-700">Tour Starts</span>
                        <span class="text-blue-600">{{ Str::limit($tour->start_location, 20) }}</span>
                    </div>
                </div>
            @endif
            @if($tour->start_time)
                <div class="flex items-start gap-1.5">
                    <i class="fa-regular fa-clock text-brand-btn mt-0.5 flex-shrink-0"></i>
                    <div>
                        <span class="block font-medium text-gray-700">Starting Time</span>
                        <span class="text-blue-600">{{ $tour->start_time }}</span>
                    </div>
                </div>
            @endif
            @if($durationLabel)
                <div class="flex items-start gap-1.5">
                    <i class="fa-regular fa-calendar text-brand-btn mt-0.5 flex-shrink-0"></i>
                    <div>
                        <span class="block font-medium text-gray-700">Duration</span>
                        <span class="text-blue-600">{{ $durationLabel }}</span>
                    </div>
                </div>
            @endif
            @if($languages)
                <div class="flex items-start gap-1.5">
                    <i class="fa-solid fa-language text-brand-btn mt-0.5 flex-shrink-0"></i>
                    <div>
                        <span class="block font-medium text-gray-700">Live Tour Guide</span>
                        <span class="text-blue-600">{{ $languages }}</span>
                    </div>
                </div>
            @elseif($tour->end_location)
                <div class="flex items-start gap-1.5">
                    <i class="fa-solid fa-flag-checkered text-brand-btn mt-0.5 flex-shrink-0"></i>
                    <div>
                        <span class="block font-medium text-gray-700">Ending place</span>
                        <span class="text-blue-600">{{ Str::limit($tour->end_location, 20) }}</span>
                    </div>
                </div>
            @endif
        </div>

        {{-- Price + CTA --}}
        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
            <div>
                <span class="text-xs text-gray-400">From</span>
                <span class="text-xl font-bold text-gray-900 ml-1">{{ number_format($price, 2) }} {{ $currency }}</span>
            </div>
            <a href="{{ $tourUrl }}" class="inline-flex items-center px-4 py-2 bg-brand-btn hover:bg-brand-btn-hover text-white text-sm font-semibold rounded transition-colors">
                View Tour
            </a>
        </div>
    </div>
</article>
