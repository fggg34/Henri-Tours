@php
    $headerPhone = \App\Models\Setting::get('contact_phone', '+355 69 238 0166');
    $headerPhoneTel = preg_replace('/[^0-9+]/', '', $headerPhone) ?: '';
    $siteName = \App\Models\Setting::get('site_name', config('app.name'));
@endphp
<header class="sticky top-0 z-50 bg-white border-b border-gray-100 shadow-sm" x-data="{ mobileOpen: false }">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-[68px]">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-1 flex-shrink-0">
                @if($siteLogo = \App\Models\Setting::get('site_logo'))
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($siteLogo) }}" alt="{{ $siteName }}" class="h-9 w-auto" />
                @else
                    <span class="text-xl font-bold tracking-tight">
                        <span class="text-brand-navy">albania </span><span class="text-brand-btn">in</span><span class="text-brand-navy">b</span><svg class="inline-block w-4 h-4 -mt-0.5 text-brand-btn" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg><span class="text-brand-navy">und</span>
                    </span>
                @endif
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden lg:flex lg:items-center lg:gap-1">
                <a href="{{ route('tours.index') }}" class="px-4 py-2 text-[15px] font-medium text-gray-700 hover:text-brand-navy border-b-2 {{ request()->is('tours*') ? 'border-brand-navy text-brand-navy' : 'border-transparent' }} transition-colors">Our Tours</a>
                <a href="{{ route('confirmed-departures') }}" class="px-4 py-2 text-[15px] font-medium text-gray-700 hover:text-brand-navy border-b-2 {{ request()->is('confirmed-departures*') ? 'border-brand-navy text-brand-navy' : 'border-transparent' }} transition-colors">Discounted Dates</a>
                <a href="{{ route('blog.index') }}" class="px-4 py-2 text-[15px] font-medium text-gray-700 hover:text-brand-navy border-b-2 {{ request()->is('blog*') ? 'border-brand-navy text-brand-navy' : 'border-transparent' }} transition-colors">The Inbound Guide</a>
                <a href="{{ route('contact') }}" class="px-4 py-2 text-[15px] font-medium text-gray-700 hover:text-brand-navy border-b-2 {{ request()->is('contact*') ? 'border-brand-navy text-brand-navy' : 'border-transparent' }} transition-colors">Contact us</a>
            </div>

            {{-- Right side --}}
            <div class="hidden lg:flex lg:items-center lg:gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 text-gray-600 hover:bg-brand-navy hover:text-white transition-colors" title="Dashboard">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 text-gray-600 hover:bg-brand-navy hover:text-white transition-colors" title="Login">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </a>
                @endauth
            </div>

            {{-- Mobile hamburger --}}
            <button @click="mobileOpen = !mobileOpen" type="button" class="lg:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100 transition-colors">
                <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </nav>

    {{-- Mobile overlay --}}
    <div x-show="mobileOpen" x-cloak x-transition @click="mobileOpen = false" class="lg:hidden fixed inset-0 z-[9998] bg-black/30"></div>

    {{-- Mobile side panel --}}
    <div x-show="mobileOpen" x-cloak
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
         class="lg:hidden fixed top-0 right-0 bottom-0 z-[9999] w-72 max-w-[85vw] bg-white shadow-xl flex flex-col">
        <div class="flex items-center justify-between px-4 py-4 border-b border-gray-100">
            <span class="text-lg font-bold text-brand-navy">Menu</span>
            <button @click="mobileOpen = false" class="p-2 -mr-2 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto py-3">
            <a href="{{ route('tours.index') }}" @click="mobileOpen = false" class="block px-5 py-3 text-[15px] font-medium {{ request()->is('tours*') ? 'text-brand-btn bg-red-50' : 'text-gray-700 hover:bg-gray-50' }}">Our Tours</a>
            <a href="{{ route('confirmed-departures') }}" @click="mobileOpen = false" class="block px-5 py-3 text-[15px] font-medium {{ request()->is('confirmed-departures*') ? 'text-brand-btn bg-red-50' : 'text-gray-700 hover:bg-gray-50' }}">Discounted Dates</a>
            <a href="{{ route('blog.index') }}" @click="mobileOpen = false" class="block px-5 py-3 text-[15px] font-medium {{ request()->is('blog*') ? 'text-brand-btn bg-red-50' : 'text-gray-700 hover:bg-gray-50' }}">The Inbound Guide</a>
            <a href="{{ route('contact') }}" @click="mobileOpen = false" class="block px-5 py-3 text-[15px] font-medium {{ request()->is('contact*') ? 'text-brand-btn bg-red-50' : 'text-gray-700 hover:bg-gray-50' }}">Contact us</a>
            <a href="{{ route('about') }}" @click="mobileOpen = false" class="block px-5 py-3 text-[15px] font-medium {{ request()->is('about*') ? 'text-brand-btn bg-red-50' : 'text-gray-700 hover:bg-gray-50' }}">About Us</a>
        </div>
        <div class="flex-shrink-0 border-t border-gray-100 p-4 space-y-2">
            @if($headerPhone)
            <a href="tel:{{ $headerPhoneTel }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-brand-navy">
                <i class="fa-solid fa-phone text-xs"></i> {{ $headerPhone }}
            </a>
            @endif
            @auth
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-sm font-medium text-brand-navy">
                    <i class="fa-solid fa-user text-xs"></i> My Account
                </a>
            @else
                <a href="{{ route('login') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-brand-navy">
                    <i class="fa-solid fa-user text-xs"></i> Sign in
                </a>
            @endauth
        </div>
    </div>
</header>
