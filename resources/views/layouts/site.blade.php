<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('meta')
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('description', \App\Models\Setting::get('site_tagline', 'Book Your Day Trips & Vacation Packages'))">
    <link rel="canonical" href="{{ request()->url() }}">
    @php
        $ogImage = trim((string) view()->yieldContent('og_image'));
        $ogImage = $ogImage ?: \App\Models\Setting::get('seo_og_image', '');
        $ogImageUrl = $ogImage ? asset('storage/' . ltrim($ogImage, '/')) : '';
        $ogTitle = strip_tags(trim((string) view()->yieldContent('title', ''))) ?: \App\Models\Setting::get('seo_default_title', config('app.name'));
        $ogDescription = strip_tags(trim((string) view()->yieldContent('description', ''))) ?: \App\Models\Setting::get('seo_default_description', 'Book Your Day Trips & Vacation Packages');
    @endphp
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:url" content="{{ request()->url() }}">
    @if($ogImageUrl)<meta property="og:image" content="{{ $ogImageUrl }}">@endif
    @include('layouts.partials.favicon')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=open-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white text-gray-800">
    @include('layouts.partials.site-nav')

    @yield('hero')

    <main>
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    @stack('scripts')
</body>
</html>
