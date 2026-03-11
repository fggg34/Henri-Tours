@extends('layouts.site')

@section('title', 'Confirmed Departures & Discounted Rates - ' . config('app.name'))
@section('description', 'Browse our confirmed group tour departures with guaranteed discounted rates. Book your Albanian adventure today.')

@section('hero')
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 pt-6">
    <section class="relative w-full h-[75vh] flex items-center justify-center rounded-2xl overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1551632811-561732d1e306?w=1920&q=80');"></div>
        <div class="absolute inset-0 bg-black/40 rounded-2xl"></div>

        <div class="relative z-10 w-full px-4 sm:px-6 lg:px-8 py-16 text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-4">Organized Group Tours & Confirmed Departures – Discounted Rates in All Dates</h1>
            <p class="text-base md:text-lg text-white/90 max-w-2xl mx-auto mb-8 leading-relaxed">Here we have listed all our confirmed dates with guaranteed departures – Take advantage of special discounts available only on these dates!</p>

            <div class="flex items-center justify-center gap-6 md:gap-10">
                <img src="https://albaniainbound.com/wp-content/uploads/2026/01/Albania-leading-travel-agency.svg" alt="Albania's leading Travel agency" class="h-14 md:h-[72px] w-auto" />
                <img src="https://albaniainbound.com/wp-content/uploads/2026/01/Best-Price.svg" alt="Best price guarantee" class="h-14 md:h-[72px] w-auto" />
            </div>
        </div>
    </section>
</div>

{{-- Global info bar (light version) --}}
<div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-4 py-3 text-xs md:text-sm text-gray-700">

            <div class="group relative flex items-center gap-2 md:gap-3 cursor-default">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-brand-navy/10">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.999 511.999" class="w-4 h-4" fill="currentColor"><g><g><path d="M399.647,227.207c-53.203,0-93.887,22.533-93.887,84.499v88.88c0,61.966,40.684,84.499,93.887,84.499 c52.576,0,93.887-22.533,93.887-84.499v-88.88C493.535,249.74,452.223,227.207,399.647,227.207z M430.943,400.587L430.943,400.587 c0,20.654-11.892,30.043-31.295,30.043c-19.403,0-30.67-9.389-30.67-30.043v-88.88c0-20.656,11.267-30.044,30.67-30.044 c19.403,0,31.295,9.389,31.295,30.044V400.587z"></path></g></g><g><g><path d="M363.345,0c-11.267,0-21.282,5.007-26.289,15.648L117.359,466.933c-1.878,3.756-3.13,8.137-3.13,11.892 c0,15.648,13.77,33.174,35.052,33.174c11.892,0,23.785-6.259,28.166-15.648L397.77,45.066c1.877-3.756,2.502-8.137,2.502-11.893 C400.272,13.144,380.869,0,363.345,0z"></path></g></g><g><g><path d="M112.351,25.662c-53.203,0-93.887,22.533-93.887,84.499v88.88c0,61.966,40.685,84.499,93.887,84.499 c52.577,0,93.887-22.534,93.887-84.499v-88.88C206.239,48.195,164.929,25.662,112.351,25.662z M143.648,199.042 c0,20.656-11.892,30.043-31.295,30.043c-19.403,0-30.67-9.389-30.67-30.043v-88.88c0-20.656,11.267-30.044,30.67-30.044 c19.403,0,31.295,9.389,31.295,30.044V199.042z"></path></g></g></svg>
                </span>
                <button type="button" class="inline-flex items-center gap-1 font-semibold text-brand-navy"><span>Book with only a 10% deposit</span><svg class="w-3 h-3 text-gray-400" viewBox="0 0 12 12" fill="none"><path fill="currentColor" fill-opacity="0.4" fill-rule="evenodd" d="M6 0a6 6 0 106 6 6.007 6.007 0 00-6-6m0 9.5A.75.75 0 116 8a.75.75 0 010 1.5m.5-2.581a.5.5 0 01.3-.459A2 2 0 104 4.629a.5.5 0 001 0 1 1 0 111.4.915 1.5 1.5 0 00-.9 1.375.5.5 0 001 0" clip-rule="evenodd"/></svg></button>
                <div class="pointer-events-none absolute left-0 top-full mt-2 w-72 md:w-80 rounded-md bg-white text-gray-800 shadow-xl px-3 py-3 text-xs leading-relaxed opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-150 z-20 border border-gray-100">Secure your booking with a small payment and pay the remaining balance only when the tour starts.</div>
            </div>

            <div class="group relative flex items-center gap-2 md:gap-3 cursor-default">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-brand-navy/10"><svg viewBox="0 0 24 25" class="w-4 h-4"><path fill="currentColor" fill-rule="evenodd" d="M21.5.7h-19A2.5 2.5 0 000 3.2v19a2.5 2.5 0 002.5 2.5h19a2.5 2.5 0 002.5-2.5v-19A2.5 2.5 0 0021.5.7zm-3 9.5H14a1 1 0 110-2h4.5a1 1 0 110 2zm0 9a1 1 0 100-2H14a1 1 0 100 2zM11.3 6.3l-3 4a1 1 0 01-.729.4H7.5a1 1 0 01-.707-.293l-1.5-1.5a1 1 0 111.414-1.415l.685.685L9.7 5.1a1 1 0 111.6 1.2zm-3 14l3-4a1 1 0 00-1.6-1.2l-2.308 3.078-.685-.685a1 1 0 00-1.414 1.414l1.5 1.5A1.018 1.018 0 008.3 20.3z" clip-rule="evenodd"/></svg></span>
                <button type="button" class="inline-flex items-center gap-1 font-semibold text-brand-navy"><span>Easy Booking &amp; Cancellation</span><svg class="w-3 h-3 text-gray-400" viewBox="0 0 12 12" fill="none"><path fill="currentColor" fill-opacity="0.4" fill-rule="evenodd" d="M6 0a6 6 0 106 6 6.007 6.007 0 00-6-6m0 9.5A.75.75 0 116 8a.75.75 0 010 1.5m.5-2.581a.5.5 0 01.3-.459A2 2 0 104 4.629a.5.5 0 001 0 1 1 0 111.4.915 1.5 1.5 0 00-.9 1.375.5.5 0 001 0" clip-rule="evenodd"/></svg></button>
                <div class="pointer-events-none absolute left-0 top-full mt-2 w-72 md:w-80 rounded-md bg-white text-gray-800 shadow-xl px-3 py-3 text-xs leading-relaxed opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-150 z-20 border border-gray-100">Multi-day tour deposits are fully refundable up to 1 month before travel and can be easily cancelled online.</div>
            </div>

            <div class="group relative flex items-center gap-2 md:gap-3 cursor-default">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-brand-navy/10"><svg viewBox="0 0 512 512" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="m512 112v65h-512v-65c0-33.1 26.9-60 60-60h35v23c0 19.3 15.7 35 35 35s35-15.7 35-35v-23h182v23c0 19.3 15.7 35 35 35s35-15.7 35-35v-23h35c33.1 0 60 26.9 60 60zm-130-12c13.8 0 25-11.2 25-25v-50c0-13.8-11.2-25-25-25s-25 11.2-25 25v50c0 13.8 11.2 25 25 25zm-252 0c13.8 0 25-11.2 25-25v-50c0-13.8-11.2-25-25-25s-25 11.2-25 25v50c0 13.8 11.2 25 25 25zm-130 87h512v265c0 33.1-26.9 60-60 60h-392c-33.1 0-60-26.9-60-60zm386 80c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm-110-205c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm-110-205c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm-110-205c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10z"/></svg></span>
                <button type="button" class="inline-flex items-center gap-1 font-semibold text-brand-navy"><span>Flexible &amp; Confirmed Departures</span><svg class="w-3 h-3 text-gray-400" viewBox="0 0 12 12" fill="none"><path fill="currentColor" fill-opacity="0.4" fill-rule="evenodd" d="M6 0a6 6 0 106 6 6.007 6.007 0 00-6-6m0 9.5A.75.75 0 116 8a.75.75 0 010 1.5m.5-2.581a.5.5 0 01.3-.459A2 2 0 104 4.629a.5.5 0 001 0 1 1 0 111.4.915 1.5 1.5 0 00-.9 1.375.5.5 0 001 0" clip-rule="evenodd"/></svg></button>
                <div class="pointer-events-none absolute left-0 top-full mt-2 w-72 md:w-80 rounded-md bg-white text-gray-800 shadow-xl px-3 py-3 text-xs leading-relaxed opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-150 z-20 border border-gray-100">Choose from several confirmed departures each month and pick the date that works best for you. Instantly confirmed and easy to reschedule.</div>
            </div>

            <div class="group relative flex items-center gap-2 md:gap-3 cursor-default">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-brand-navy/10"><svg viewBox="0 0 428.16 428.16" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M393.8,110.208c-0.512-11.264-0.512-22.016-0.512-32.768c0-8.704-6.656-15.36-15.36-15.36c-64,0-112.64-18.432-153.088-57.856c-6.144-5.632-15.36-5.632-21.504,0C162.888,43.648,114.248,62.08,50.248,62.08c-8.704,0-15.36,6.656-15.36,15.36c0,10.752,0,21.504-0.512,32.768c-2.048,107.52-5.12,254.976,174.592,316.928l5.12,1.024l5.12-1.024C398.408,365.184,395.848,218.24,393.8,110.208z M201.8,259.2c-3.072,2.56-6.656,4.096-10.752,4.096h-0.512c-4.096,0-8.192-2.048-10.752-5.12l-47.616-52.736l23.04-20.48l37.376,41.472l82.944-78.848l20.992,22.528L201.8,259.2z"/></svg></span>
                <button type="button" class="inline-flex items-center gap-1 font-semibold text-brand-navy"><span>Trusted by Travelers</span><svg class="w-3 h-3 text-gray-400" viewBox="0 0 12 12" fill="none"><path fill="currentColor" fill-opacity="0.4" fill-rule="evenodd" d="M6 0a6 6 0 106 6 6.007 6.007 0 00-6-6m0 9.5A.75.75 0 116 8a.75.75 0 010 1.5m.5-2.581a.5.5 0 01.3-.459A2 2 0 104 4.629a.5.5 0 001 0 1 1 0 111.4.915 1.5 1.5 0 00-.9 1.375.5.5 0 001 0" clip-rule="evenodd"/></svg></button>
                <div class="pointer-events-none absolute left-0 top-full mt-2 w-72 md:w-80 rounded-md bg-white text-gray-800 shadow-xl px-3 py-3 text-xs leading-relaxed opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-150 z-20 border border-gray-100">We've served over 20,000 travelers in the past 2 years and received more than 2,000 reviews online.</div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('content')

@if($tours->isEmpty())
<div class="max-w-5xl mx-auto px-4 py-20 text-center">
    <i class="fa-solid fa-calendar-xmark text-5xl text-gray-300 mb-4"></i>
    <h2 class="text-2xl font-bold text-gray-700 mb-2">No confirmed departures yet</h2>
    <p class="text-gray-500 mb-6">Check back later for new discounted departure dates.</p>
    <a href="{{ route('tours.index') }}" class="inline-flex px-6 py-3 bg-brand-btn hover:bg-brand-btn-hover text-white font-medium rounded-lg transition-colors">Browse all tours</a>
</div>
@else

{{-- ========== TOUR SELECTOR + DATES ========== --}}
<section class="bg-gray-50 py-12 md:py-16" x-data="confirmedDepartures()" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section heading --}}
        <div class="mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Select Tour</h2>
        </div>

        {{-- Tour Cards Swiper --}}
        <div class="relative">
            @if($tours->count() > 3)
            <div class="hidden md:flex items-center justify-end gap-2 mb-4">
                <button type="button" class="departures-prev w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-brand-navy hover:border-brand-navy transition-colors">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </button>
                <button type="button" class="departures-next w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-brand-navy hover:border-brand-navy transition-colors">
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </button>
            </div>
            @endif

            <div class="swiper departures-swiper overflow-visible" style="padding: 4px; margin: -4px;">
                <div class="swiper-wrapper">
                    @foreach($tours as $idx => $tour)
                    @php
                        $firstImg = $tour->images->first();
                        $imageUrl = $firstImg?->url ?? 'https://placehold.co/600x400/e2e8f0/64748b?text=Tour';
                        $categoryName = $tour->category?->name;
                        $shortDesc = $tour->short_description ?? \Illuminate\Support\Str::limit(strip_tags($tour->description), 120);
                    @endphp
                    <div class="swiper-slide" style="height: auto;">
                        <div @click="selectTour({{ $idx }})"
                             :class="activeTour === {{ $idx }} ? 'ring-2 ring-brand-btn ring-offset-2' : 'hover:shadow-lg'"
                             class="cursor-pointer bg-white rounded-lg overflow-hidden border border-gray-200 transition-all h-full flex flex-col">
                            <div class="relative overflow-hidden aspect-[4/3]">
                                <img src="{{ $imageUrl }}" alt="{{ $tour->title }}" class="w-full h-full object-cover" loading="lazy">
                                @if($categoryName)
                                    <span class="absolute bottom-3 left-3 inline-flex items-center px-3 py-1 text-xs font-semibold rounded bg-white/90 text-gray-800 backdrop-blur-sm">{{ $categoryName }}</span>
                                @endif
                            </div>
                            <div class="p-4 flex flex-col flex-1">
                                <h3 class="text-base font-bold text-gray-900 leading-snug mb-2 line-clamp-2">{{ $tour->title }}</h3>
                                <p class="text-sm text-gray-500 line-clamp-3 mb-4 flex-1">{{ $shortDesc }}</p>
                                <div class="flex items-center gap-2 mt-auto">
                                    <span class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded cursor-pointer">Confirmed Dates</span>
                                    <a href="{{ route('tours.show', $tour->slug) }}" class="inline-flex items-center px-3 py-1.5 bg-brand-navy text-white text-xs font-bold rounded hover:bg-brand-navy-light transition-colors" @click.stop>View Tour</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Dates Table --}}
        <div id="dates-section" class="mt-10 scroll-mt-8">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                {{-- Table header --}}
                <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 bg-gray-50 border-b border-gray-200 text-sm font-semibold text-gray-600">
                    <div class="col-span-3">Starting</div>
                    <div class="col-span-3">Ending</div>
                    <div class="col-span-3 text-center"></div>
                    <div class="col-span-3 text-right">Prices from</div>
                </div>

                {{-- Date rows - one per tour, toggled by activeTour --}}
                @foreach($toursData as $tourIdx => $tourItem)
                    <div x-show="activeTour === {{ $tourIdx }}" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        @foreach($tourItem['dates'] as $dateIdx => $date)
                        <div @click="selectDate({{ $tourIdx }}, {{ $dateIdx }})"
                             :class="activeTour === {{ $tourIdx }} && activeDate === {{ $dateIdx }} ? 'bg-blue-50 border-l-4 border-l-brand-navy' : 'border-l-4 border-l-transparent hover:bg-gray-50'"
                             class="grid grid-cols-1 md:grid-cols-12 gap-2 md:gap-4 px-4 md:px-6 py-5 border-b border-gray-100 cursor-pointer transition-colors">
                            {{-- Start date --}}
                            <div class="md:col-span-3 flex flex-wrap items-center">
                                <span class="md:hidden text-xs font-medium text-gray-400 uppercase">Starting</span>
                                <p class="text-sm text-gray-900">
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($date['start'])->format('l d') }}</span>
                                    <span class="font-bold ml-1">{{ \Carbon\Carbon::parse($date['start'])->format('F Y') }}</span>
                                </p>
                            </div>
                            {{-- End date --}}
                            <div class="md:col-span-3 flex flex-wrap items-center">
                                <span class="md:hidden text-xs font-medium text-gray-400 uppercase">Ending</span>
                                <p class="text-sm text-gray-900">
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($date['end'])->format('l d') }}</span>
                                    <span class="font-bold ml-1">{{ \Carbon\Carbon::parse($date['end'])->format('F Y') }}</span>
                                </p>
                            </div>
                            {{-- Status --}}
                            <div class="md:col-span-3 flex flex-wrap items-center gap-2 md:justify-center">
                                <span class="inline-flex items-center px-2.5 py-1 bg-red-500 text-white text-xs font-bold rounded">Sale</span>
                                <span class="text-sm text-green-700 font-medium">Available</span>
                                <span class="text-xs text-gray-400">On request</span>
                            </div>
                            {{-- Price --}}
                            <div class="md:col-span-3 text-left md:text-right">
                                <span class="md:hidden text-xs font-medium text-gray-400 uppercase">Prices from</span>
                                <p class="text-sm text-gray-400 line-through">{{ $date['original'] }}</p>
                                <p class="text-lg font-bold text-gray-900">{{ $date['price'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ========== INQUIRY FORM ========== --}}
    <div id="inquiry-form" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 scroll-mt-8">
        <div class="bg-slate-50 rounded-2xl border border-gray-200 p-6 md:p-10 shadow-sm">
            <h2 class="text-xl md:text-2xl font-bold text-brand-navy mb-1">Tour Booking & Info Inquiry</h2>
            <div x-show="selectedTourName" class="mb-6">
                <p class="text-sm text-gray-500 mt-1">
                    Tour: <span class="font-semibold text-gray-800" x-text="selectedTourName"></span>
                    <template x-if="selectedDateLabel">
                        <span> &middot; <span class="font-semibold text-gray-800" x-text="selectedDateLabel"></span></span>
                    </template>
                </p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 text-green-800 rounded-xl border border-green-100 flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('confirmed-departures.store') }}" method="POST" class="space-y-8">
                @csrf
                <input type="hidden" name="tour_id" :value="tourId">
                <input type="hidden" name="discount_id" :value="discountId">

                {{-- Personal Details --}}
                <div>
                    <h3 class="text-base font-bold text-gray-900 mb-4">Personal Details:</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                            <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                            @error('full_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1.5">Date of birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                            @error('date_of_birth')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="inquiry_email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                            <input type="email" name="email" id="inquiry_email" value="{{ old('email') }}" required
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="inquiry_phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone number</label>
                            <input type="tel" name="phone" id="inquiry_phone" value="{{ old('phone') }}" placeholder="201-555-0123"
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900"
                                data-initial-phone="{{ e(old('phone', '')) }}">
                            @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Contact Details --}}
                <div>
                    <h3 class="text-base font-bold text-gray-900 mb-4">Contact Details:</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="inquiry_address" class="block text-sm font-medium text-gray-700 mb-1.5">Address</label>
                            <input type="text" name="address" id="inquiry_address" value="{{ old('address') }}"
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                            @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="inquiry_city" class="block text-sm font-medium text-gray-700 mb-1.5">City</label>
                            <input type="text" name="city" id="inquiry_city" value="{{ old('city') }}"
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                            @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="inquiry_country" class="block text-sm font-medium text-gray-700 mb-1.5">Country</label>
                            <input type="text" name="country" id="inquiry_country" value="{{ old('country') }}"
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                            @error('country')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Room Options --}}
                <div>
                    <label for="room_option" class="block text-sm font-bold text-gray-900 mb-1.5">Room Options:</label>
                    <select name="room_option" id="room_option"
                        class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                        <option value="individual">Individual Room (Additional cost)</option>
                        <option value="shared_double">Shared / Double Room</option>
                        <option value="triple">Triple Room</option>
                    </select>
                </div>

                {{-- Message --}}
                <div>
                    <label for="inquiry_message" class="block text-sm font-bold text-gray-900 mb-1.5">Your message</label>
                    <textarea name="message" id="inquiry_message" rows="5"
                        class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900 resize-y">{{ old('message') }}</textarea>
                    @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="text-center">
                    <button type="submit" class="px-10 py-3.5 bg-brand-navy hover:bg-brand-navy-light text-white font-semibold rounded-lg transition-colors text-base">
                        SUBMIT
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@endif
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@26/build/css/intlTelInput.min.css">
<style>
.iti.iti--allow-dropdown.iti--show-flags.iti--inline-dropdown { width: 100%; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@26/build/js/intlTelInput.min.js"></script>
<script>
function confirmedDepartures() {
    return {
        activeTour: 0,
        activeDate: null,
        tours: {!! $toursJson ?? '[]' !!},
        get tourId() {
            return this.tours[this.activeTour]?.id ?? '';
        },
        get discountId() {
            if (this.activeDate === null) return '';
            var dates = this.tours[this.activeTour]?.dates ?? [];
            return dates[this.activeDate]?.id ?? '';
        },
        get selectedTourName() {
            return this.tours[this.activeTour]?.title ?? '';
        },
        get selectedDateLabel() {
            if (this.activeDate === null) return '';
            var d = this.tours[this.activeTour]?.dates?.[this.activeDate];
            return d ? d.start_label + ' – ' + d.end_label : '';
        },
        selectTour(idx) {
            this.activeTour = idx;
            this.activeDate = null;
            this.$nextTick(() => {
                var el = document.getElementById('dates-section');
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        },
        selectDate(tourIdx, dateIdx) {
            this.activeTour = tourIdx;
            this.activeDate = dateIdx;
            this.$nextTick(() => {
                var el = document.getElementById('inquiry-form');
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        },
        init() {
            this.$nextTick(() => {
                if (window.Swiper && document.querySelector('.departures-swiper')) {
                    new window.Swiper('.departures-swiper', {
                        modules: [window.SwiperNavigation],
                        slidesPerView: 1.15,
                        spaceBetween: 16,
                        navigation: { prevEl: '.departures-prev', nextEl: '.departures-next' },
                        breakpoints: {
                            640: { slidesPerView: 2.2, spaceBetween: 16 },
                            1024: { slidesPerView: 3.2, spaceBetween: 20 },
                            1280: { slidesPerView: 4, spaceBetween: 20 },
                        },
                    });
                }

                var phoneInput = document.getElementById('inquiry_phone');
                if (phoneInput && window.intlTelInput) {
                    var initialNumber = phoneInput.getAttribute('data-initial-phone') || '';
                    var iti = window.intlTelInput(phoneInput, {
                        initialCountry: 'al',
                        utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@26/build/js/utils.js'
                    });
                    if (initialNumber) iti.setNumber(initialNumber);
                    var form = phoneInput.closest('form');
                    if (form) {
                        form.addEventListener('submit', function () {
                            try {
                                var data = iti.getSelectedCountryData();
                                var dialCode = data && data.dialCode ? '+' + data.dialCode : '';
                                var digits = (phoneInput.value || '').replace(/\D/g, '');
                                if (dialCode && digits) phoneInput.value = dialCode + digits;
                            } catch (e) {}
                        });
                    }
                }
            });
        }
    };
}
</script>
@endpush
