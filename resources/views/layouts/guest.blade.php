<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name'))</title>

        @include('layouts.partials.favicon')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        @include('layouts.partials.site-nav')

        <main class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-sm mx-auto">
                <a href="{{ localized_route('home') }}" class="block text-center mb-8">
                    <span class="text-2xl font-bold text-brand-navy">{{ \App\Models\Setting::get('site_name', config('app.name')) }}</span>
                </a>

                <div class="bg-white shadow-lg rounded-md overflow-hidden border border-gray-200">
                    <div class="px-10 py-12">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </main>

        @include('layouts.partials.footer')
    </body>
</html>
