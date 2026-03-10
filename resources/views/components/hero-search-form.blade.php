@props(['action' => route('tours.index'), 'cities' => collect()])

@php
    $initialCity = request('city');
    $initialDate = request('date');
    $initialAdults = max(1, (int) request('adults', 2));
    $citiesData = $cities->map(fn ($c) => ['slug' => $c->slug, 'name' => $c->name, 'label' => $c->country ?? 'City'])->values()->toArray();
@endphp

<div class="w-full max-w-3xl mx-auto" x-data="heroSearchForm({
    action: @js($action),
    cities: @js($citiesData),
    initialCity: @js($initialCity),
    initialDate: @js($initialDate),
    initialAdults: {{ $initialAdults }},
})" x-init="init()">
    <form :action="action" method="GET" @submit="submitForm">
        <input type="hidden" name="city" :value="selectedCity">
        <input type="hidden" name="date" :value="selectedDate">

        <div class="bg-white/95 backdrop-blur rounded-lg shadow-xl p-4 md:p-5">
            <div class="flex flex-col md:flex-row gap-3 md:gap-4 items-stretch">
                {{-- Keyword / Destination --}}
                <div class="flex-1 relative">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Search your destination</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="keyword" placeholder="Keyword" value="{{ request('keyword') }}"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-brand-navy/20 focus:border-brand-navy" />
                    </div>
                </div>

                {{-- Date --}}
                <div class="flex-1 relative">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Select Date</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <input type="text" x-ref="dateInput" placeholder="Date from" readonly
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-md text-sm bg-white cursor-pointer focus:ring-2 focus:ring-brand-navy/20 focus:border-brand-navy" />
                    </div>
                </div>

                {{-- Search Button --}}
                <div class="flex items-end">
                    <button type="submit" class="w-full md:w-auto px-8 py-2.5 bg-brand-btn hover:bg-brand-btn-hover text-white font-bold rounded-md transition-colors text-sm uppercase tracking-wide">
                        Search
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
