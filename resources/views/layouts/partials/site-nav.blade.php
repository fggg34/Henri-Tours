@php
    $headerPhone = \App\Models\Setting::get('contact_phone', '+355 69 238 0166');
    $headerPhoneTel = preg_replace('/[^0-9+]/', '', $headerPhone) ?: '';
    $siteName = \App\Models\Setting::get('site_name', config('app.name'));

    $navItems = \App\Models\Setting::get('nav_menu_items', '');
    $navItems = is_string($navItems) ? (json_decode($navItems, true) ?: []) : $navItems;
    if (empty($navItems)) {
        $navItems = [
            ['type' => 'link', 'label' => __('navigation.our_tours'), 'url' => '/tours', 'children' => []],
            ['type' => 'link', 'label' => __('navigation.discounted_dates'), 'url' => '/confirmed-departures', 'children' => []],
            ['type' => 'link', 'label' => __('navigation.the_inbound_guide'), 'url' => '/blog', 'children' => []],
            ['type' => 'link', 'label' => __('navigation.contact_us'), 'url' => '/contact', 'children' => []],
        ];
    }

    $navUrl = function ($item) {
        $url = $item['url'] ?? '#';
        return localized_url($url);
    };
    $navIsActive = function ($url) {
        $path = ltrim(parse_url($url, PHP_URL_PATH) ?? '', '/');
        if ($path === '' || $path === 'index') return request()->path() === '' || request()->path() === 'index';
        return request()->is($path . '*');
    };
@endphp
<header class="sticky top-0 z-50 bg-white{{ request()->routeIs('home') ? '' : ' shadow-md' }}" x-data="{ mobileOpen: false, langOpen: false }">
    {{-- Thin dark grey top bar --}}
    <div class="h-0.5 bg-gray-500"></div>

    <nav class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-[68px]">

            {{-- Logo --}}
            <a href="{{ localized_route('home') }}" class="flex items-center h-[30px] flex-shrink-0">
                @if($siteLogo = \App\Models\Setting::get('site_logo'))
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($siteLogo) }}" alt="{{ $siteName }}" class="h-[30px] w-auto" />
                @else
                    <span class="text-lg font-bold tracking-tight lowercase leading-none flex items-center h-[30px] [&_svg]:h-[18px] [&_svg]:w-[18px]">
                        <span class="text-brand-logo-light">albania </span>
                        <span class="text-brand-navy">in</span>
                        <svg class="inline-block -mt-0.5 text-brand-btn" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        <span class="text-brand-navy">und</span>
                    </span>
                @endif
            </a>

            {{-- Right side: Menu + Language selector + Account icon --}}
            <div class="hidden lg:flex lg:items-center" x-data="{ openDropdown: null }">
                {{-- Desktop Navigation - from Settings --}}
                @foreach($navItems as $idx => $item)
                    @if($idx > 0)<span class="w-px h-4 bg-gray-200 flex-shrink-0"></span>@endif
                    @if(($item['type'] ?? 'link') === 'dropdown' && !empty($item['children'] ?? []))
                        <div class="relative" @click.away="openDropdown = null">
                            <button type="button" @click="openDropdown = openDropdown === {{ $idx }} ? null : {{ $idx }}"
                                class="flex items-center gap-1 px-4 py-2 text-[15px] font-medium text-brand-logo-light hover:text-brand-navy transition-colors"
                                :class="{ 'text-brand-navy': openDropdown === {{ $idx }} }"
                                aria-expanded="false" aria-haspopup="true">
                                {{ $item['label'] ?? '' }}
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                            <div x-show="openDropdown === {{ $idx }}" x-cloak x-transition
                                class="absolute left-0 top-full mt-1 min-w-[180px] py-1 bg-white rounded-md shadow-lg border border-gray-100 ring-1 ring-black ring-opacity-5">
                                @foreach($item['children'] ?? [] as $child)
                                    <a href="{{ $navUrl($child) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-brand-navy">{{ $child['label'] ?? '' }}</a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $navUrl($item) }}" class="px-4 py-2 text-[15px] font-medium text-brand-logo-light hover:text-brand-navy transition-colors">{{ $item['label'] ?? '' }}</a>
                    @endif
                @endforeach
                {{-- Language selector --}}
                <x-language-switcher />
                {{-- Account icon --}}
                @auth
                    <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 text-gray-600 hover:bg-brand-navy hover:text-white transition-colors" title="{{ __('navigation.dashboard') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 text-gray-600 hover:bg-brand-navy hover:text-white transition-colors" title="{{ __('navigation.login') }}">
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
            <span class="text-lg font-bold text-brand-navy">{{ __('navigation.menu') }}</span>
            <button @click="mobileOpen = false" class="p-2 -mr-2 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto py-3">
            @foreach($navItems as $item)
                @if(($item['type'] ?? 'link') === 'dropdown' && !empty($item['children'] ?? []))
                    <div class="px-5 pt-3 pb-1">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">{{ $item['label'] ?? '' }}</span>
                    </div>
                    @foreach($item['children'] ?? [] as $child)
                        @php $childUrl = $navUrl($child); @endphp
                        <a href="{{ $childUrl }}" @click="mobileOpen = false" class="block px-5 pl-8 py-2.5 text-[15px] font-medium {{ $navIsActive($childUrl) ? 'text-brand-btn bg-red-50' : 'text-gray-700 hover:bg-gray-50' }}">{{ $child['label'] ?? '' }}</a>
                    @endforeach
                @else
                    @php $itemUrl = $navUrl($item); @endphp
                    <a href="{{ $itemUrl }}" @click="mobileOpen = false" class="block px-5 py-3 text-[15px] font-medium {{ $navIsActive($itemUrl) ? 'text-brand-btn bg-red-50' : 'text-gray-700 hover:bg-gray-50' }}">{{ $item['label'] ?? '' }}</a>
                @endif
            @endforeach
        </div>
        <div class="flex-shrink-0 border-t border-gray-100 p-4 space-y-2">
            @if($headerPhone)
            <a href="tel:{{ $headerPhoneTel }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-brand-navy">
                <i class="fa-solid fa-phone text-xs"></i> {{ $headerPhone }}
            </a>
            @endif
            @auth
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-sm font-medium text-brand-navy">
                    <i class="fa-solid fa-user text-xs"></i> {{ __('navigation.my_account') }}
                </a>
            @else
                <a href="{{ route('login') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-brand-navy">
                    <i class="fa-solid fa-user text-xs"></i> {{ __('navigation.sign_in') }}
                </a>
            @endauth
            <div class="pt-3 border-t border-gray-100 mt-2">
                <x-language-switcher />
            </div>
        </div>
    </div>
</header>
