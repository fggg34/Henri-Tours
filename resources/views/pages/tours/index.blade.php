@extends('layouts.site')

@section('title', 'Tours - ' . config('app.name'))
@section('description', 'Browse our selection of tours and book your next adventure.')

@section('hero')
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 pt-6">
    <section class="relative w-full h-[75vh] flex items-center justify-center rounded-2xl overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=80');"></div>
        <div class="absolute inset-0 bg-black/40 rounded-2xl"></div>

        <div class="relative z-10 w-full px-4 sm:px-6 lg:px-8 py-16 text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-4">Best Tours & Vacation Packages in Albania - Best Selection & Lowest Prices Guaranteed</h1>
            <p class="text-base md:text-lg text-white/90 max-w-2xl mx-auto mb-8 leading-relaxed">Choose from a wide range of tours, activities, and vacation packages across Albania and the Balkan region.</p>

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
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.999 511.999" class="w-4 h-4" fill="currentColor">
                        <g><g><path d="M399.647,227.207c-53.203,0-93.887,22.533-93.887,84.499v88.88c0,61.966,40.684,84.499,93.887,84.499 c52.576,0,93.887-22.533,93.887-84.499v-88.88C493.535,249.74,452.223,227.207,399.647,227.207z M430.943,400.587L430.943,400.587 c0,20.654-11.892,30.043-31.295,30.043c-19.403,0-30.67-9.389-30.67-30.043v-88.88c0-20.656,11.267-30.044,30.67-30.044 c19.403,0,31.295,9.389,31.295,30.044V400.587z"></path></g></g>
                        <g><g><path d="M363.345,0c-11.267,0-21.282,5.007-26.289,15.648L117.359,466.933c-1.878,3.756-3.13,8.137-3.13,11.892 c0,15.648,13.77,33.174,35.052,33.174c11.892,0,23.785-6.259,28.166-15.648L397.77,45.066c1.877-3.756,2.502-8.137,2.502-11.893 C400.272,13.144,380.869,0,363.345,0z"></path></g></g>
                        <g><g><path d="M112.351,25.662c-53.203,0-93.887,22.533-93.887,84.499v88.88c0,61.966,40.685,84.499,93.887,84.499 c52.577,0,93.887-22.534,93.887-84.499v-88.88C206.239,48.195,164.929,25.662,112.351,25.662z M143.648,199.042 c0,20.656-11.892,30.043-31.295,30.043c-19.403,0-30.67-9.389-30.67-30.043v-88.88c0-20.656,11.267-30.044,30.67-30.044 c19.403,0,31.295,9.389,31.295,30.044V199.042z"></path></g></g>
                    </svg>
                </span>
                <button type="button" class="inline-flex items-center gap-1 font-semibold text-brand-navy">
                    <span>Book with only a 10% deposit</span>
                    <svg class="w-3 h-3 text-gray-400" viewBox="0 0 12 12" fill="none"><path fill="currentColor" fill-opacity="0.4" fill-rule="evenodd" d="M6 0a6 6 0 106 6 6.007 6.007 0 00-6-6m0 9.5A.75.75 0 116 8a.75.75 0 010 1.5m.5-2.581a.5.5 0 01.3-.459A2 2 0 104 4.629a.5.5 0 001 0 1 1 0 111.4.915 1.5 1.5 0 00-.9 1.375.5.5 0 001 0" clip-rule="evenodd"/></svg>
                </button>
                <div class="pointer-events-none absolute left-0 top-full mt-2 w-72 md:w-80 rounded-md bg-white text-gray-800 shadow-xl px-3 py-3 text-xs leading-relaxed opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-150 z-20 border border-gray-100">
                    Secure your booking with a small payment and pay the remaining balance only when the tour starts.
                </div>
            </div>

            <div class="group relative flex items-center gap-2 md:gap-3 cursor-default">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-brand-navy/10">
                    <svg viewBox="0 0 24 25" class="w-4 h-4">
                        <path fill="currentColor" fill-rule="evenodd" d="M21.5.7h-19A2.5 2.5 0 000 3.2v19a2.5 2.5 0 002.5 2.5h19a2.5 2.5 0 002.5-2.5v-19A2.5 2.5 0 0021.5.7zm-3 9.5H14a1 1 0 110-2h4.5a1 1 0 110 2zm0 9a1 1 0 100-2H14a1 1 0 100 2zM11.3 6.3l-3 4a1 1 0 01-.729.4H7.5a1 1 0 01-.707-.293l-1.5-1.5a1 1 0 111.414-1.415l.685.685L9.7 5.1a1 1 0 111.6 1.2zm-3 14l3-4a1 1 0 00-1.6-1.2l-2.308 3.078-.685-.685a1 1 0 00-1.414 1.414l1.5 1.5A1.018 1.018 0 008.3 20.3z" clip-rule="evenodd"/>
                    </svg>
                </span>
                <button type="button" class="inline-flex items-center gap-1 font-semibold text-brand-navy">
                    <span>Easy Booking &amp; Cancellation</span>
                    <svg class="w-3 h-3 text-gray-400" viewBox="0 0 12 12" fill="none"><path fill="currentColor" fill-opacity="0.4" fill-rule="evenodd" d="M6 0a6 6 0 106 6 6.007 6.007 0 00-6-6m0 9.5A.75.75 0 116 8a.75.75 0 010 1.5m.5-2.581a.5.5 0 01.3-.459A2 2 0 104 4.629a.5.5 0 001 0 1 1 0 111.4.915 1.5 1.5 0 00-.9 1.375.5.5 0 001 0" clip-rule="evenodd"/></svg>
                </button>
                <div class="pointer-events-none absolute left-0 top-full mt-2 w-72 md:w-80 rounded-md bg-white text-gray-800 shadow-xl px-3 py-3 text-xs leading-relaxed opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-150 z-20 border border-gray-100">
                    Multi-day tour deposits are fully refundable up to 1 month before travel and can be easily cancelled online.
                </div>
            </div>

            <div class="group relative flex items-center gap-2 md:gap-3 cursor-default">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-brand-navy/10">
                    <svg viewBox="0 0 512 512" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="m512 112v65h-512v-65c0-33.1 26.9-60 60-60h35v23c0 19.3 15.7 35 35 35s35-15.7 35-35v-23h182v23c0 19.3 15.7 35 35 35s35-15.7 35-35v-23h35c33.1 0 60 26.9 60 60zm-130-12c13.8 0 25-11.2 25-25v-50c0-13.8-11.2-25-25-25s-25 11.2-25 25v50c0 13.8 11.2 25 25 25zm-252 0c13.8 0 25-11.2 25-25v-50c0-13.8-11.2-25-25-25s-25 11.2-25 25v50c0 13.8 11.2 25 25 25zm-130 87h512v265c0 33.1-26.9 60-60 60h-392c-33.1 0-60-26.9-60-60zm386 80c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm-110-205c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm-110-205c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10z"/>
                    </svg>
                </span>
                <button type="button" class="inline-flex items-center gap-1 font-semibold text-brand-navy">
                    <span>Flexible &amp; Confirmed Departures</span>
                    <svg class="w-3 h-3 text-gray-400" viewBox="0 0 12 12" fill="none"><path fill="currentColor" fill-opacity="0.4" fill-rule="evenodd" d="M6 0a6 6 0 106 6 6.007 6.007 0 00-6-6m0 9.5A.75.75 0 116 8a.75.75 0 010 1.5m.5-2.581a.5.5 0 01.3-.459A2 2 0 104 4.629a.5.5 0 001 0 1 1 0 111.4.915 1.5 1.5 0 00-.9 1.375.5.5 0 001 0" clip-rule="evenodd"/></svg>
                </button>
                <div class="pointer-events-none absolute left-0 top-full mt-2 w-72 md:w-80 rounded-md bg-white text-gray-800 shadow-xl px-3 py-3 text-xs leading-relaxed opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-150 z-20 border border-gray-100">
                    Choose from several confirmed departures each month and pick the date that works best for you. Instantly confirmed and easy to reschedule.
                </div>
            </div>

            <div class="group relative flex items-center gap-2 md:gap-3 cursor-default">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-brand-navy/10">
                    <svg viewBox="0 0 428.16 428.16" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M393.8,110.208c-0.512-11.264-0.512-22.016-0.512-32.768c0-8.704-6.656-15.36-15.36-15.36c-64,0-112.64-18.432-153.088-57.856c-6.144-5.632-15.36-5.632-21.504,0C162.888,43.648,114.248,62.08,50.248,62.08c-8.704,0-15.36,6.656-15.36,15.36c0,10.752,0,21.504-0.512,32.768c-2.048,107.52-5.12,254.976,174.592,316.928l5.12,1.024l5.12-1.024C398.408,365.184,395.848,218.24,393.8,110.208z M201.8,259.2c-3.072,2.56-6.656,4.096-10.752,4.096h-0.512c-4.096,0-8.192-2.048-10.752-5.12l-47.616-52.736l23.04-20.48l37.376,41.472l82.944-78.848l20.992,22.528L201.8,259.2z"/>
                    </svg>
                </span>
                <button type="button" class="inline-flex items-center gap-1 font-semibold text-brand-navy">
                    <span>Trusted by Travelers</span>
                    <svg class="w-3 h-3 text-gray-400" viewBox="0 0 12 12" fill="none"><path fill="currentColor" fill-opacity="0.4" fill-rule="evenodd" d="M6 0a6 6 0 106 6 6.007 6.007 0 00-6-6m0 9.5A.75.75 0 116 8a.75.75 0 010 1.5m.5-2.581a.5.5 0 01.3-.459A2 2 0 104 4.629a.5.5 0 001 0 1 1 0 111.4.915 1.5 1.5 0 00-.9 1.375.5.5 0 001 0" clip-rule="evenodd"/></svg>
                </button>
                <div class="pointer-events-none absolute left-0 top-full mt-2 w-72 md:w-80 rounded-md bg-white text-gray-800 shadow-xl px-3 py-3 text-xs leading-relaxed opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-150 z-20 border border-gray-100">
                    We've served over 20,000 travelers in the past 2 years and received more than 2,000 reviews online.
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('content')

{{-- Explore ways to travel --}}
<section class="py-10">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl md:text-3xl text-center mb-10 md:mb-14 text-gray-400 font-light">Explore <span class="font-bold text-gray-900 italic">ways to travel</span></h2>

        @if($categories->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ min($categories->count(), 4) }} gap-6 md:gap-8">
            @foreach($categories as $cat)
            <a href="{{ route('tours.category', $cat->slug) }}" class="group block bg-white rounded-xl border border-gray-200 shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="aspect-[4/3] overflow-hidden bg-gray-100">
                    @if($cat->image_url)
                        <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <i class="fa-solid fa-mountain-sun text-4xl text-gray-400"></i>
                        </div>
                    @endif
                </div>
                <div class="p-5 text-center">
                    <h3 class="text-base md:text-lg font-bold text-gray-900 mb-1">{{ $cat->name }}</h3>
                    @if($cat->description)
                        <p class="text-sm text-gray-500 mb-4">{{ $cat->description }}</p>
                    @endif
                    <span class="inline-block px-6 py-2.5 bg-brand-btn text-white text-sm font-semibold rounded-full hover:bg-brand-btn-hover transition-colors">View All</span>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- Organized Group Tours / Confirmed Departures --}}
<section class="pb-14 md:pb-20">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 md:p-12">
            <div class="flex flex-col lg:flex-row items-center gap-10 lg:gap-16">

                <div class="flex-1">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 leading-snug mb-6">Organized Group Tours<br>With Confirmed Departure Dates</h2>

                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-sm md:text-base text-gray-700">Confirmed multi-day group tours</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-sm md:text-base text-gray-700">Fixed start dates</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-sm md:text-base text-gray-700">Guaranteed departures</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-sm md:text-base text-gray-700">Perfect for solo travelers</span>
                        </li>
                    </ul>

                    <a href="{{ route('confirmed-departures') }}" class="inline-block px-6 py-2.5 bg-brand-btn text-white text-sm font-semibold rounded-full hover:bg-brand-btn-hover transition-colors">View Available Dates</a>
                </div>

                <div class="flex-1 max-w-md lg:max-w-lg">
                    <img src="https://albaniainbound.com/wp-content/uploads/2026/02/AD108803.JPG-1.webp" alt="Organized Group Tours" class="w-full rounded-xl shadow-lg rotate-2 hover:rotate-0 transition-transform duration-300" />
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
