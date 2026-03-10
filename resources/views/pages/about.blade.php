@php
    $heroTitle = \App\Models\Setting::get('page_about_hero_title', 'Our Story');
    $heroImage = \App\Models\Setting::get('page_about_hero_image', '');
    $heroBg = $heroImage ? \Illuminate\Support\Facades\Storage::disk('public')->url($heroImage) : 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=1920&h=600&fit=crop';
    $introLabel = \App\Models\Setting::get('page_about_intro_label', 'Nice to meet you');
    $introTitle = \App\Models\Setting::get('page_about_intro_title', 'We started with a simple idea: share the Albania we love');
    $introContent = \App\Models\Setting::get('page_about_intro_content', '');
    $introImage = \App\Models\Setting::get('page_about_intro_image', '');
    $introImageUrl = $introImage ? \Illuminate\Support\Facades\Storage::disk('public')->url($introImage) : 'https://images.unsplash.com/photo-1528543606781-2f6e6857f318?w=700&h=500&fit=crop';
    $introBadgeTitle = \App\Models\Setting::get('page_about_intro_badge_title', 'Since day one');
    $introBadgeSubtitle = \App\Models\Setting::get('page_about_intro_badge_subtitle', 'Passionate about Albania');
    $valuesLabel = \App\Models\Setting::get('page_about_values_label', 'What matters to us');
    $valuesTitle = \App\Models\Setting::get('page_about_values_title', "We're not a big corporation. We're a small team that genuinely cares.");
    $values = \App\Models\Setting::get('page_about_values', '');
    $values = is_string($values) ? (json_decode($values, true) ?: []) : $values;
    if (empty($values)) {
        $values = [
            ['icon' => 'fa-heart', 'title' => 'Honesty over hype', 'description' => "We'll tell you honestly which tours are worth it and which spots are overhyped."],
            ['icon' => 'fa-people-group', 'title' => 'Small groups, real connections', 'description' => "We keep groups small on purpose. You're not a ticket number."],
            ['icon' => 'fa-seedling', 'title' => 'Respect the places we visit', 'description' => 'We work with local families and support the communities that make Albania special.'],
        ];
    }
    $quoteText = \App\Models\Setting::get('page_about_quote_text', '');
    $expectLabel = \App\Models\Setting::get('page_about_expect_label', 'What to expect');
    $expectTitle = \App\Models\Setting::get('page_about_expect_title', "When you book with us, here's what you get");
    $expectItems = \App\Models\Setting::get('page_about_expect_items', '');
    $expectItems = is_string($expectItems) ? (json_decode($expectItems, true) ?: []) : $expectItems;
    if (empty($expectItems)) {
        $expectItems = [
            ['title' => 'Guides who actually love this', 'description' => "Our guides aren't reading from a script. They're locals who are genuinely passionate."],
            ['title' => 'No surprise costs', 'description' => 'The price you see is the price you pay.'],
            ['title' => 'Flexibility when life happens', 'description' => 'Plans change, we get it. We make rescheduling as painless as possible.'],
            ['title' => 'A real person to talk to', 'description' => "Have a question? You'll reach a real person, not a chatbot."],
        ];
    }
    $expectImage1 = \App\Models\Setting::get('page_about_expect_image_1', '');
    $expectImage1Url = $expectImage1 ? \Illuminate\Support\Facades\Storage::disk('public')->url($expectImage1) : 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=500&fit=crop';
    $expectImage2 = \App\Models\Setting::get('page_about_expect_image_2', '');
    $expectImage2Url = $expectImage2 ? \Illuminate\Support\Facades\Storage::disk('public')->url($expectImage2) : 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=400&h=500&fit=crop';
@endphp
@extends('layouts.site')

@section('title', \App\Models\Setting::get('page_about_seo_title') ?: ('About Us - ' . config('app.name')))
@section('description', \App\Models\Setting::get('page_about_seo_description') ?: 'The story behind our tours, our team, and why we love sharing Albania with you.')
@if(\App\Models\Setting::get('page_about_seo_og_image'))@section('og_image', \App\Models\Setting::get('page_about_seo_og_image'))@endif

@section('content')
{{-- Hero --}}
<!-- <div class="relative w-full overflow-hidden bg-brand-footer" style="height: 360px;">
    <div class="absolute inset-0 bg-cover bg-center opacity-60" style="background-image: url('{{ $heroBg }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
    <div class="absolute inset-0 flex items-end">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pb-10">
            <nav class="text-sm mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center gap-1.5">
                    <li><a href="{{ route('home') }}" class="text-white/70 hover:text-white transition">Home</a></li>
                    <li class="text-white/50">/</li>
                    <li class="text-white">About us</li>
                </ol>
            </nav>
            <h1 class="text-4xl md:text-5xl font-bold text-white" style="color: #fff !important;">{{ $heroTitle }}</h1>
        </div>
    </div>
</div> -->

@if($introTitle || $introContent || $introImage)
{{-- Personal intro --}}
<section class="py-16 md:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <div>
                @if($introLabel)
                    <p class="text-xs font-medium uppercase tracking-wider text-brand-btn mb-3">{{ $introLabel }}</p>
                @endif
                @if($introTitle)
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">{{ $introTitle }}</h2>
                @endif
                @if($introContent)
                    <div class="space-y-4 text-gray-600 leading-relaxed">
                        @foreach(explode("\n\n", $introContent) as $para)
                            @if(trim($para))
                                <p>{{ $para }}</p>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="relative">
                <div class="rounded-2xl overflow-hidden shadow-xl">
                    <img src="{{ $introImageUrl }}" alt="Friends travelling together" class="w-full h-full object-cover" style="aspect-ratio: 7/5;">
                </div>
                @if($introBadgeTitle || $introBadgeSubtitle)
                <div class="absolute -bottom-6 -left-6 bg-white rounded-xl shadow-lg px-5 py-4 hidden lg:block">
                    @if($introBadgeTitle)<p class="text-sm font-semibold text-gray-900">{{ $introBadgeTitle }}</p>@endif
                    @if($introBadgeSubtitle)<p class="text-xs text-gray-500">{{ $introBadgeSubtitle }}</p>@endif
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

@if(!empty($values))
{{-- What matters to us --}}
<section class="py-16 md:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            @if($valuesLabel)
                <p class="text-xs font-medium uppercase tracking-wider text-brand-btn mb-3">{{ $valuesLabel }}</p>
            @endif
            @if($valuesTitle)
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $valuesTitle }}</h2>
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($values as $val)
            <div class="bg-white rounded-2xl p-8 border border-gray-100">
                @if(!empty($val['icon']))
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center mb-5">
                    <i class="fa-solid {{ $val['icon'] }} text-xl text-brand-btn"></i>
                </div>
                @endif
                @if(!empty($val['title']))
                    <h3 class="text-lg font-bold text-gray-900 mb-3">{{ $val['title'] }}</h3>
                @endif
                @if(!empty($val['description']))
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $val['description'] }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($quoteText)
{{-- Personal quote / philosophy --}}
<section class="py-16 md:py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-8">
            <i class="fa-solid fa-quote-left text-2xl text-brand-btn"></i>
        </div>
        <blockquote class="text-xl md:text-2xl font-medium text-gray-900 leading-relaxed mb-6">
            "{{ $quoteText }}"
        </blockquote>
        <p class="text-sm text-gray-500">The {{ \App\Models\Setting::get('site_name', config('app.name')) }} team</p>
    </div>
</section>
@endif

@if(!empty($expectItems) || $expectTitle)
{{-- What to expect --}}
<section class="py-16 md:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <div class="order-2 lg:order-1 relative">
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-2xl overflow-hidden">
                        <img src="{{ $expectImage1Url }}" alt="Mountain landscape" class="w-full object-cover" style="aspect-ratio: 4/5;">
                    </div>
                    <div class="rounded-2xl overflow-hidden mt-8">
                        <img src="{{ $expectImage2Url }}" alt="Lake view" class="w-full object-cover" style="aspect-ratio: 4/5;">
                    </div>
                </div>
            </div>
            <div class="order-1 lg:order-2">
                @if($expectLabel)
                    <p class="text-xs font-medium uppercase tracking-wider text-brand-btn mb-3">{{ $expectLabel }}</p>
                @endif
                @if($expectTitle)
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8">{{ $expectTitle }}</h2>
                @endif
                @if(!empty($expectItems))
                <div class="space-y-6">
                    @foreach($expectItems as $item)
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fa-solid fa-check text-brand-btn text-xs"></i>
                        </div>
                        <div>
                            @if(!empty($item['title']))
                                <h4 class="font-semibold text-gray-900 mb-1">{{ $item['title'] }}</h4>
                            @endif
                            @if(!empty($item['description']))
                                <p class="text-sm text-gray-500">{{ $item['description'] }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif
@endsection
