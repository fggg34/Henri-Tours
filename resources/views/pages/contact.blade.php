@php
    $locale = app()->getLocale();
    $heroTitle = \App\Models\Setting::getTranslated('page_contact_hero_title', $locale, 'Get in touch');
    $heroSubtitle = \App\Models\Setting::getTranslated('page_contact_hero_subtitle', $locale, "We'd love to hear from you");
    $heroImage = \App\Models\Setting::get('page_contact_hero_image', '');
    $heroBg = $heroImage ? \Illuminate\Support\Facades\Storage::disk('public')->url($heroImage) : 'https://images.unsplash.com/photo-1423666639041-f56000c27a9a?w=1920&h=600&fit=crop';
    $formTitle = \App\Models\Setting::getTranslated('page_contact_form_title', $locale, 'Send us a message');
    $formDescription = \App\Models\Setting::getTranslated('page_contact_form_description', $locale, "Fill out the form below and we'll get back to you as soon as possible.");
    $sidebarTitle = \App\Models\Setting::getTranslated('page_contact_sidebar_title', $locale, 'Need quick help?');
    $sidebarDescription = \App\Models\Setting::getTranslated('page_contact_sidebar_description', $locale, 'Check our frequently asked questions for instant answers.');
    $sidebarButtonText = \App\Models\Setting::getTranslated('page_contact_sidebar_button_text', $locale, 'Browse tours');
    $sidebarButtonUrl = \App\Models\Setting::get('page_contact_sidebar_button_url', '') ?: localized_route('tours.index');
@endphp
@extends('layouts.site')

@section('title', \App\Models\Setting::getTranslated('page_contact_seo_title', $locale, '') ?: ('Contact - ' . config('app.name')))
@section('description', \App\Models\Setting::getTranslated('page_contact_seo_description', $locale, '') ?: 'Get in touch with us.')
@if(\App\Models\Setting::get('page_contact_seo_og_image'))@section('og_image', \App\Models\Setting::get('page_contact_seo_og_image'))@endif

@section('content')
{{-- Hero --}}
<div class="relative w-full overflow-hidden bg-brand-footer" style="height: 320px;">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $heroBg }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
    <div class="absolute inset-0 flex items-end">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pb-10">
            <nav class="text-sm mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center gap-1.5">
                    <li><a href="{{ localized_route('home') }}" class="text-white/70 hover:text-white transition">Home</a></li>
                    <li class="text-white/50">/</li>
                    <li class="text-white">Contact</li>
                </ol>
            </nav>
            <h1 class="text-4xl md:text-5xl font-bold text-white" style="color: #fff !important;">{{ $heroTitle }}</h1>
            @if($heroSubtitle)
                <p class="mt-2 text-lg text-white/80">{{ $heroSubtitle }}</p>
            @endif
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">

        {{-- Form --}}
        <div class="lg:col-span-3">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $formTitle }}</h2>
            <p class="text-gray-500 mb-8">{{ $formDescription }}</p>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 text-green-800 rounded-xl border border-green-100 flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ localized_route('contact.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-navy focus:ring-blue-500 py-3"
                            placeholder="Your name">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-navy focus:ring-blue-500 py-3"
                            placeholder="your@email.com">
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1.5">Subject</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-navy focus:ring-blue-500 py-3"
                        placeholder="How can we help?">
                    @error('subject')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1.5">Message</label>
                    <textarea name="message" id="message" rows="5" required
                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-navy focus:ring-blue-500"
                        placeholder="Tell us more about your inquiry...">{{ old('message') }}</textarea>
                    @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="px-8 py-3.5 bg-brand-btn hover:bg-brand-btn-hover text-white font-medium rounded-xl transition-colors">
                    Send message
                </button>
            </form>
        </div>

        {{-- Contact info sidebar --}}
        <div class="lg:col-span-2">
            <div class="sticky top-24 space-y-6">
                <div class="rounded-2xl bg-white border border-gray-100 p-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Contact details</h3>
                    <div class="space-y-5">
                        @if(\App\Models\Setting::get('contact_email'))
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center flex-shrink-0 shadow-sm">
                                <i class="fa-solid fa-envelope text-brand-btn"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-gray-400 mb-0.5">Email</p>
                                <a href="mailto:{{ \App\Models\Setting::get('contact_email') }}" class="text-sm text-gray-700 hover:text-brand-btn transition break-all">{{ \App\Models\Setting::get('contact_email') }}</a>
                            </div>
                        </div>
                        @endif

                        @if(\App\Models\Setting::get('contact_phone'))
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center flex-shrink-0 shadow-sm">
                                <i class="fa-solid fa-phone text-brand-btn"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-gray-400 mb-0.5">Phone</p>
                                <a href="tel:{{ \App\Models\Setting::get('contact_phone') }}" class="text-sm text-gray-700 hover:text-brand-btn transition">{{ \App\Models\Setting::get('contact_phone') }}</a>
                            </div>
                        </div>
                        @endif

                        @if(\App\Models\Setting::get('contact_address'))
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center flex-shrink-0 shadow-sm">
                                <i class="fa-solid fa-location-dot text-brand-btn"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-gray-400 mb-0.5">Address</p>
                                <p class="text-sm text-gray-700">{{ \App\Models\Setting::get('contact_address') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Quick links --}}
                <div class="rounded-2xl bg-white border border-gray-100 p-8 text-center">
                    <i class="fa-solid fa-headset text-3xl text-brand-btn mb-4"></i>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $sidebarTitle }}</h3>
                    <p class="text-sm text-gray-500 mb-5">{{ $sidebarDescription }}</p>
                    <a href="{{ localized_url($sidebarButtonUrl) }}" class="inline-flex px-6 py-2.5 bg-brand-btn hover:bg-brand-btn-hover text-white text-sm font-medium rounded-lg transition-colors">
                        {{ $sidebarButtonText }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
