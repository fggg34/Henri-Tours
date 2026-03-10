@php
    $heroTitle = \App\Models\Setting::get('page_faq_hero_title', 'Frequently Asked Questions');
    $heroSubtitle = \App\Models\Setting::get('page_faq_hero_subtitle', 'Everything you need to know');
    $heroImage = \App\Models\Setting::get('page_faq_hero_image', '');
    $heroBg = $heroImage ? \Illuminate\Support\Facades\Storage::disk('public')->url($heroImage) : 'https://images.unsplash.com/photo-1488085061387-422e29b40080?w=1920&h=600&fit=crop';
    $sections = \App\Models\Setting::get('page_faq_sections', '');
    $sections = is_string($sections) ? (json_decode($sections, true) ?: []) : $sections;
    if (empty($sections)) {
        $sections = [
            ['category_label' => 'Booking & payments', 'category_title' => 'How booking works', 'items' => [
                ['q' => 'How do I book a tour?', 'a' => 'Simply browse our tours, select the one you like, pick your preferred date and number of travelers, and complete the booking form.'],
                ['q' => 'What payment methods do you accept?', 'a' => 'We accept all major credit and debit cards, as well as bank transfers.'],
            ]],
            ['category_label' => 'Cancellations & changes', 'category_title' => 'Flexibility when you need it', 'items' => [
                ['q' => 'What is your cancellation policy?', 'a' => 'Most tours offer free cancellation up to 7 days before the tour date.'],
            ]],
            ['category_label' => 'Tours & experiences', 'category_title' => 'About our tours', 'items' => [
                ['q' => 'Are your tours guided?', 'a' => 'Most tours include professional local guides.'],
            ]],
        ];
    }
    $ctaTitle = \App\Models\Setting::get('page_faq_cta_title', 'Still have questions?');
    $ctaDescription = \App\Models\Setting::get('page_faq_cta_description', "Can't find what you're looking for? Our team is happy to help.");
    $ctaButtonText = \App\Models\Setting::get('page_faq_cta_button_text', 'Contact us');
    $ctaButtonUrl = \App\Models\Setting::get('page_faq_cta_button_url', '') ?: route('contact');
@endphp
@extends('layouts.site')

@section('title', \App\Models\Setting::get('page_faq_seo_title') ?: ('FAQ - ' . config('app.name')))
@section('description', \App\Models\Setting::get('page_faq_seo_description') ?: 'Frequently asked questions about our tours, bookings and services.')
@if(\App\Models\Setting::get('page_faq_seo_og_image'))@section('og_image', \App\Models\Setting::get('page_faq_seo_og_image'))@endif

@section('content')
{{-- Hero --}}
<div class="relative w-full overflow-hidden bg-brand-footer" style="height: 320px;">
    <div class="absolute inset-0 bg-cover bg-center opacity-50" style="background-image: url('{{ $heroBg }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
    <div class="absolute inset-0 flex items-end">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pb-10">
            <nav class="text-sm mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center gap-1.5">
                    <li><a href="{{ route('home') }}" class="text-white/70 hover:text-white transition">Home</a></li>
                    <li class="text-white/50">/</li>
                    <li class="text-white">FAQ</li>
                </ol>
            </nav>
            <h1 class="text-4xl md:text-5xl font-bold text-white" style="color: #fff !important;">{{ $heroTitle }}</h1>
            @if($heroSubtitle)
                <p class="mt-2 text-lg text-white/80">{{ $heroSubtitle }}</p>
            @endif
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    @foreach($sections as $sIdx => $section)
    <section class="mb-12">
        @if(!empty($section['category_label']))
            <p class="text-xs font-medium uppercase tracking-wider text-gray-400 mb-2">{{ $section['category_label'] }}</p>
        @endif
        @if(!empty($section['category_title']))
            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $section['category_title'] }}</h2>
        @endif
        @if(!empty($section['items']))
        <div class="space-y-3" x-data="{ open: null }">
            @foreach($section['items'] as $i => $faq)
                <div class="rounded-xl border border-gray-100 bg-white overflow-hidden">
                    <button @click="open === 's{{ $sIdx }}_{{ $i }}' ? open = null : open = 's{{ $sIdx }}_{{ $i }}'"
                        class="w-full flex items-center justify-between gap-4 px-6 py-4 text-left">
                        <span class="font-medium text-gray-900">{{ $faq['q'] ?? '' }}</span>
                        <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-200" :class="open === 's{{ $sIdx }}_{{ $i }}' && 'rotate-180'"></i>
                    </button>
                    <div x-show="open === 's{{ $sIdx }}_{{ $i }}'" x-collapse>
                        <div class="px-6 pb-4 text-sm text-gray-600 leading-relaxed">{{ $faq['a'] ?? '' }}</div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    </section>
    @endforeach

    {{-- CTA --}}
    <section class="mt-16 rounded-2xl bg-white border border-gray-100 p-8 md:p-12 text-center">
        <i class="fa-solid fa-comment-dots text-3xl text-brand-btn mb-4"></i>
        <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $ctaTitle }}</h2>
        <p class="text-gray-500 mb-6 max-w-md mx-auto">{{ $ctaDescription }}</p>
        <a href="{{ $ctaButtonUrl }}" class="inline-flex px-8 py-3.5 bg-brand-btn hover:bg-brand-btn-hover text-white font-medium rounded-xl transition-colors">
            {{ $ctaButtonText }}
        </a>
    </section>
</div>
@endsection
