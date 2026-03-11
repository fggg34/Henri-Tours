@extends('layouts.site')

@push('styles')
<style>
.tours-filter-bar > .relative:not(.tours-filter-category) {
    max-height: 42px;
}
.tours-date-calendar-wrap .flatpickr-calendar {
    position: relative !important;
    top: auto !important;
    left: auto !important;
}
</style>
@endpush

@section('title', $category->name . ' - ' . config('app.name'))
@section('description', $category->description ?? 'Browse our ' . $category->name)

@php
    $heroTitle = $category->hero_title ?: \App\Models\Setting::get('page_tours_hero_title', 'Best Tours & Vacation Packages in Albania - Best Selection & Lowest Prices Guaranteed');
    $heroSubtitle = $category->hero_subtitle ?: \App\Models\Setting::get('page_tours_hero_subtitle', 'Choose from a wide range of tours, activities, and vacation packages across Albania and the Balkan region.');
    $defaultToursImage = \App\Models\Setting::get('page_tours_hero_image', '');
    $defaultHeroBg = $defaultToursImage ? \Illuminate\Support\Facades\Storage::disk('public')->url($defaultToursImage) : 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=80';
    $heroBg = $category->hero_image_url ?: $defaultHeroBg;
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
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="tourCategoryFilters()" x-init="init()">

    <!-- <div class="mb-6">
        <a href="{{ route('tours.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-brand-navy transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            All Tours
        </a>
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mt-2">{{ $category->name }}</h2>
        @if($category->description)
            <div class="text-gray-500 mt-1 prose prose-sm max-w-none dark:prose-invert">
                {!! \Filament\Forms\Components\RichEditor\RichContentRenderer::make($category->description)->toUnsafeHtml() !!}
            </div>
        @endif
    </div> -->

    {{-- Filter bar --}}
    <div class="tours-filter-bar flex flex-wrap items-center gap-3 pb-6 border-b border-gray-200">

        @if($categories->isNotEmpty())
        <div class="relative tours-filter-category">
            <button @click="openCategory = !openCategory" type="button"
                class="inline-flex items-center gap-2 px-5 py-3 bg-blue-50 border-2 rounded-full text-sm font-semibold text-brand-navy hover:bg-blue-100 hover:border-blue-400 transition-all shadow-sm border-brand-navy bg-blue-100">
                <i class="fa-solid fa-route text-brand-navy"></i>
                <span>{{ $category->name }}</span>
                <i class="fa-solid fa-chevron-down text-[10px] text-brand-navy ml-1"></i>
            </button>
            <div x-show="openCategory" @click.outside="openCategory = false" x-transition
                class="absolute left-0 top-full mt-2 z-50 bg-white rounded-xl shadow-xl border-2 border-blue-200 p-3 min-w-[220px]">
                <a href="{{ route('tours.index') }}" class="block w-full text-left px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 text-gray-700">
                    All categories
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('tours.category', $cat->slug) }}" class="block w-full text-left px-4 py-2.5 rounded-lg text-sm transition-colors {{ $cat->id === $category->id ? 'bg-blue-50 text-brand-navy font-medium' : 'hover:bg-gray-50 text-gray-700' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" type="button"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border rounded-full text-sm font-medium text-gray-700 hover:border-gray-400 transition-colors"
                :class="selectedDurations.length > 0 ? 'border-gray-900 text-gray-900' : 'border-gray-300'">
                <span x-text="selectedDurations.length > 0 ? 'Duration (' + selectedDurations.length + ')' : 'Duration'"></span>
                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 ml-1"></i>
            </button>
            <div x-show="open" @click.outside="open = false" x-transition
                class="absolute left-0 top-full mt-2 z-50 bg-white rounded-xl shadow-xl border border-gray-200 p-3 min-w-[200px]">
                @foreach($durationOptions as $opt)
                    <label class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" value="{{ $opt['value'] }}"
                            class="h-4 w-4 rounded border-gray-300 text-brand-navy focus:ring-blue-500"
                            :checked="selectedDurations.includes('{{ $opt['value'] }}')"
                            @change="toggleDuration('{{ $opt['value'] }}')">
                        <span class="text-sm text-gray-700">{{ $opt['label'] }}</span>
                    </label>
                @endforeach
                @if($durationOptions->isEmpty())
                    <p class="px-3 py-2 text-sm text-gray-400">No options available</p>
                @endif
            </div>
        </div>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" type="button"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border rounded-full text-sm font-medium text-gray-700 hover:border-gray-400 transition-colors"
                :class="selectedSeasons.length > 0 ? 'border-gray-900 text-gray-900' : 'border-gray-300'">
                <span x-text="selectedSeasons.length > 0 ? 'Season (' + selectedSeasons.length + ')' : 'Season'"></span>
                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 ml-1"></i>
            </button>
            <div x-show="open" @click.outside="open = false" x-transition
                class="absolute left-0 top-full mt-2 z-50 bg-white rounded-xl shadow-xl border border-gray-200 p-3 min-w-[200px]">
                @foreach($seasonOptions as $opt)
                    <label class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" value="{{ $opt['value'] }}"
                            class="h-4 w-4 rounded border-gray-300 text-brand-navy focus:ring-blue-500"
                            :checked="selectedSeasons.includes('{{ $opt['value'] }}')"
                            @change="toggleSeason('{{ $opt['value'] }}')">
                        <span class="text-sm text-gray-700">{{ $opt['label'] }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
            <span class="text-sm font-medium text-gray-700">On Sale</span>
            <button type="button" @click="onSale = !onSale; applyFilters()"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                :class="onSale ? 'bg-brand-btn' : 'bg-gray-200'"
                role="switch" :aria-checked="onSale">
                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                    :class="onSale ? 'translate-x-5' : 'translate-x-0'"></span>
            </button>
        </label>

        @if(request()->has('duration') || request()->has('season') || request()->boolean('on_sale') || request('q') || request('city'))
            <a href="{{ route('tours.category', $category->slug) }}" class="text-sm text-gray-500 hover:text-gray-900 underline underline-offset-2 ml-1">Clear Filters</a>
        @endif
    </div>

    {{-- Results count + sort --}}
    <div class="flex items-center justify-between mt-6 mb-6">
        <p class="text-sm text-gray-600">
            <span class="font-semibold text-gray-900">{{ $tours->total() }}</span> Tours
        </p>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" type="button"
                class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-700 hover:border-gray-400 transition-colors">
                <span>Sort: <span class="font-medium" x-text="sortLabel()">Most Popular</span></span>
                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
            </button>
            <div x-show="open" @click.outside="open = false" x-transition
                class="absolute right-0 top-full mt-1 z-50 bg-white rounded-xl shadow-xl border border-gray-200 py-1 min-w-[180px]">
                <template x-for="opt in sortOptions" :key="opt.value">
                    <button @click="currentSort = opt.value; open = false; applyFilters()"
                        class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 transition-colors"
                        :class="currentSort === opt.value ? 'text-gray-900 font-medium' : 'text-gray-600'"
                        x-text="opt.label"></button>
                </template>
            </div>
        </div>
    </div>

    {{-- Tour grid --}}
    @php
        $searchParams = array_filter([
            'city' => request('city'),
            'date' => request('date'),
            'adults' => request('adults'),
        ]);
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse($tours as $tour)
            <x-tour-card :tour="$tour" :queryParams="$searchParams" :wishlisted="in_array($tour->id, $wishlistedIds ?? [])" />
        @empty
            <p class="col-span-full text-gray-500 text-center py-12">No tours found. Try adjusting your filters.</p>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $tours->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
function tourCategoryFilters() {
    return {
        openCategory: false,
        selectedDurations: @json(array_map('strval', (array) request('duration', []))),
        selectedSeasons: @json(array_map('strval', (array) request('season', []))),
        onSale: {{ request()->boolean('on_sale') ? 'true' : 'false' }},
        currentSort: '{{ request('sort', 'popular') }}',
        sortOptions: [
            { value: 'popular', label: 'Most Popular' },
            { value: 'newest', label: 'Newest' },
            { value: 'price_low', label: 'Price: Low to High' },
            { value: 'price_high', label: 'Price: High to Low' },
        ],

        toggleDuration(val) {
            const idx = this.selectedDurations.indexOf(val);
            if (idx > -1) this.selectedDurations.splice(idx, 1);
            else this.selectedDurations.push(val);
            this.applyFilters();
        },

        toggleSeason(val) {
            const idx = this.selectedSeasons.indexOf(val);
            if (idx > -1) this.selectedSeasons.splice(idx, 1);
            else this.selectedSeasons.push(val);
            this.applyFilters();
        },

        sortLabel() {
            const found = this.sortOptions.find(o => o.value === this.currentSort);
            return found ? found.label : 'Most Popular';
        },

        applyFilters() {
            const params = new URLSearchParams();
            this.selectedDurations.forEach(d => params.append('duration[]', d));
            this.selectedSeasons.forEach(s => params.append('season[]', s));
            if (this.onSale) params.set('on_sale', '1');
            if (this.currentSort && this.currentSort !== 'popular') params.set('sort', this.currentSort);
            const q = '{{ request('q', '') }}';
            if (q) params.set('q', q);
            const city = '{{ request('city', '') }}';
            if (city) params.set('city', city);
            window.location.href = '{{ route('tours.category', $category->slug) }}' + (params.toString() ? '?' + params.toString() : '');
        },

        init() {}
    }
}
</script>
@endpush
