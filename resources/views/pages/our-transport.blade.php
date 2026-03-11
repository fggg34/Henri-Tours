@php
    $heroTitle = \App\Models\Setting::get('page_our_transport_hero_title', 'Our Transport');
    $heroSubtitle = \App\Models\Setting::get('page_our_transport_hero_subtitle', 'Travel comfortably across Albania with our modern fleet. From minivans to coaches, we ensure a smooth ride for every journey.');
    $heroImage = \App\Models\Setting::get('page_our_transport_hero_image', '');
    $heroBg = $heroImage ? \Illuminate\Support\Facades\Storage::disk('public')->url($heroImage) : 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=80';

    $seoTitle = \App\Models\Setting::get('page_our_transport_seo_title', '');
    $seoDesc = \App\Models\Setting::get('page_our_transport_seo_description', '');

    $vehicles = \App\Models\Setting::get('page_our_transport_vehicles', '');
    $vehicles = is_string($vehicles) ? (json_decode($vehicles, true) ?: []) : $vehicles;

    $formTitle = \App\Models\Setting::get('page_our_transport_form_title', 'Book Your Transport Today');
    $formSubtitle = \App\Models\Setting::get('page_our_transport_form_subtitle', 'Let us handle your transport so you can enjoy Albania stress-free');

    $featureSectionTitle = \App\Models\Setting::get('page_our_transport_feature_section_title', 'Why our transport stands out');
    $featureCards = \App\Models\Setting::get('page_our_transport_feature_cards', '');
    $featureCards = is_string($featureCards) ? (json_decode($featureCards, true) ?: []) : $featureCards;
    if (empty($featureCards)) {
        $featureCards = [
            ['icon' => 'fa-shield-halved', 'icon_image' => '', 'title' => 'Safe & Reliable', 'description' => 'Regularly maintained vehicles for worry-free travel'],
            ['icon' => 'fa-couch', 'icon_image' => '', 'title' => 'Modern & Comfortable', 'description' => 'Air conditioning, comfortable seating, cozy ambient'],
            ['icon' => 'fa-id-badge', 'icon_image' => '', 'title' => 'Experienced Drivers', 'description' => 'Professional expert drivers for Albania & The Balkans'],
            ['icon' => 'fa-users', 'icon_image' => '', 'title' => 'All group sizes', 'description' => 'All size vehicles available from 3 - 55 seater'],
        ];
    }

    $storageUrl = fn($path) => $path ? \Illuminate\Support\Facades\Storage::disk('public')->url($path) : '';
@endphp
@extends('layouts.site')

@section('title', $seoTitle ?: ('Our Transport - ' . config('app.name')))
@section('description', $seoDesc ?: 'Travel comfortably across Albania with our modern fleet. From minivans to coaches, we ensure a smooth ride for every journey.')
@if(\App\Models\Setting::get('page_our_transport_seo_og_image'))
@section('og_image', \App\Models\Setting::get('page_our_transport_seo_og_image'))
@endif

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
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Vehicles Grid (4 per row) --}}
    @if(!empty($vehicles))
    <section class="py-16 md:py-20">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
            @foreach($vehicles as $vehicle)
            @php
                $images = $vehicle['gallery_images'] ?? [];
                $images = is_array($images) ? $images : [];
                $title = $vehicle['title'] ?? '';
                $features = $vehicle['features'] ?? [];
                $features = is_array($features) ? $features : [];
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                {{-- Swiper gallery --}}
                <div class="relative aspect-[4/3] bg-gray-100">
                    @if(count($images) > 0)
                    <div class="vehicle-swiper swiper h-full w-full" data-vehicle-id="vehicle-{{ $loop->index }}">
                        <div class="swiper-wrapper">
                            @foreach($images as $img)
                            <div class="swiper-slide">
                                <img src="{{ $storageUrl($img) }}" alt="{{ $title }}" class="w-full h-full object-cover" />
                            </div>
                            @endforeach
                        </div>
                        @if(count($images) > 1)
                        <button type="button" class="vehicle-swiper-prev absolute left-2 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-white/90 shadow-md flex items-center justify-center text-gray-700 hover:bg-white transition">
                            <i class="fa-solid fa-chevron-left text-sm"></i>
                        </button>
                        <button type="button" class="vehicle-swiper-next absolute right-2 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-white/90 shadow-md flex items-center justify-center text-gray-700 hover:bg-white transition">
                            <i class="fa-solid fa-chevron-right text-sm"></i>
                        </button>
                        @endif
                    </div>
                    @else
                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                        <i class="fa-solid fa-truck text-4xl"></i>
                    </div>
                    @endif
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    @if($title)
                    <h3 class="text-base font-bold text-gray-900 mb-3">{{ $title }}</h3>
                    @endif
                    @if(!empty($features))
                    <ul class="space-y-1.5 text-sm text-gray-600 mb-5 flex-1">
                        @foreach($features as $f)
                        @if(!empty($f['label']) && isset($f['value']))
                        <li><span class="font-medium text-gray-700">{{ $f['label'] }}:</span> {{ $f['value'] }}</li>
                        @endif
                        @endforeach
                    </ul>
                    @endif
                    <button type="button" data-book-vehicle data-vehicle-title="{{ e($title) }}"
                        class="w-full py-3 px-4 bg-brand-navy hover:bg-brand-btn text-white font-semibold rounded-lg text-sm transition-colors">
                        Book This Vehicle
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Why our transport stands out (2x2 feature cards) --}}
    @if(!empty($featureCards))
    <section class="py-16 pt-0">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 text-center mb-10">{{ $featureSectionTitle }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mx-auto">
            @foreach($featureCards as $card)
            @php
                $iconImage = $card['icon_image'] ?? '';
                $iconImage = is_array($iconImage) ? ($iconImage[0] ?? '') : $iconImage;
                $iconClass = $card['icon'] ?? '';
                $cardTitle = $card['title'] ?? '';
                $cardDesc = $card['description'] ?? '';
            @endphp
            <div class="flex gap-5 p-6 bg-gray-50 rounded-xl border border-gray-100">
                <div class="flex-shrink-0 w-14 h-14 flex items-center justify-center">
                    @if($iconImage)
                    <img src="{{ $storageUrl($iconImage) }}" alt="{{ $cardTitle }}" class="w-full h-full object-contain" />
                    @elseif($iconClass)
                    <i class="fa-solid {{ $iconClass }} text-2xl text-gray-900"></i>
                    @endif
                </div>
                <div class="min-w-0">
                    @if($cardTitle)<h3 class="text-base font-bold text-gray-900 mb-1">{{ $cardTitle }}</h3>@endif
                    @if($cardDesc)<p class="text-sm text-gray-600">{{ $cardDesc }}</p>@endif
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Booking Form --}}
    <section id="transport-form" class="pb-20 scroll-mt-24">
        <div class="max-w-4xl mx-auto">
            <div class="bg-slate-50 rounded-2xl p-8 md:p-10 border border-gray-200 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $formTitle }}</h2>
                <p class="text-gray-500 text-sm mb-8">{{ $formSubtitle }}</p>

                @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 text-green-800 rounded-xl border border-green-100 flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('our-transport.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="preferred_vehicle" id="preferred_vehicle" value="{{ old('preferred_vehicle') }}">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fa-solid fa-user text-gray-400 mr-2"></i>Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 py-3 text-gray-900">
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fa-solid fa-envelope text-gray-400 mr-2"></i>Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 py-3 text-gray-900">
                            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fa-solid fa-phone text-gray-400 mr-2"></i>Telephone <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="telephone" id="telephone" value="{{ old('telephone') }}" required placeholder="+355 67 212 3456"
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 py-3 text-gray-900">
                            @error('telephone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label for="travel_date" class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fa-solid fa-calendar text-gray-400 mr-2"></i>Travel Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="travel_date" id="travel_date" value="{{ old('travel_date') }}" required
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 py-3 text-gray-900">
                            @error('travel_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="travel_end_date" class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fa-solid fa-calendar text-gray-400 mr-2"></i>Travel End Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="travel_end_date" id="travel_end_date" value="{{ old('travel_end_date') }}" required
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 py-3 text-gray-900">
                            @error('travel_end_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="pickup_location" class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fa-solid fa-map-marker-alt text-gray-400 mr-2"></i>Pickup Location <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pickup_location" id="pickup_location" value="{{ old('pickup_location') }}" required
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 py-3 text-gray-900">
                            @error('pickup_location')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label for="dropoff_location" class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fa-solid fa-map-marker-alt text-gray-400 mr-2"></i>Dropoff Location <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="dropoff_location" id="dropoff_location" value="{{ old('dropoff_location') }}" required
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 py-3 text-gray-900">
                            @error('dropoff_location')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="preferred_vehicle_display" class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fa-solid fa-car text-gray-400 mr-2"></i>Preferred Vehicle
                            </label>
                            <input type="text" id="preferred_vehicle_display" value="{{ old('preferred_vehicle') }}" readonly placeholder="Select a vehicle above"
                                class="w-full rounded-lg border-gray-200 bg-gray-50 shadow-sm py-3 text-gray-600 cursor-not-allowed">
                        </div>
                        <div>
                            <label for="group_size" class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fa-solid fa-users text-gray-400 mr-2"></i>Group Size
                            </label>
                            <input type="number" name="group_size" id="group_size" value="{{ old('group_size') }}" min="1" placeholder="e.g. 10"
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 py-3 text-gray-900">
                            @error('group_size')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fa-solid fa-comment text-gray-400 mr-2"></i>Message
                        </label>
                        <textarea name="message" id="message" rows="4" placeholder="Message / Inquiry"
                            class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 py-3 text-gray-900 resize-y">{{ old('message') }}</textarea>
                        @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full md:w-auto px-10 py-3.5 bg-brand-navy hover:bg-brand-btn text-white font-semibold rounded-lg transition-colors">
                            Book Your Transport Today
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-book-vehicle]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var title = this.getAttribute('data-vehicle-title') || '';
            document.getElementById('preferred_vehicle').value = title;
            document.getElementById('preferred_vehicle_display').value = title;
            document.getElementById('transport-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
    if (typeof Swiper === 'undefined') return;
    const containers = document.querySelectorAll('.vehicle-swiper');
    containers.forEach(function(el) {
        const prev = el.querySelector('.vehicle-swiper-prev');
        const next = el.querySelector('.vehicle-swiper-next');
        if (el.querySelectorAll('.swiper-slide').length <= 1) return;
        new Swiper(el, {
            modules: [window.SwiperNavigation, window.SwiperEffectFade],
            effect: 'fade',
            fadeEffect: { crossFade: true },
            loop: true,
            navigation: prev && next ? { prevEl: prev, nextEl: next } : false
        });
    });
});
</script>
@endpush
@endsection
