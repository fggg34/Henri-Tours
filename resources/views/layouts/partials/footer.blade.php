@php
    $siteName = \App\Models\Setting::get('site_name', config('app.name'));
    $contactEmail = \App\Models\Setting::get('contact_email', 'info@albaniainbound.com');
    $contactPhone = \App\Models\Setting::get('contact_phone', '+355 69 238 0166');
    $contactPhone2 = \App\Models\Setting::get('contact_phone_2', '+355 69 700 3355');
    $contactAddress = \App\Models\Setting::get('contact_address', 'St. Ymer Kurti, Tirane 1019, Albania');
    $instagramUrl = \App\Models\Setting::get('instagram_url', '');
    $facebookUrl = \App\Models\Setting::get('facebook_url', '');

    $footerMenu1 = \App\Models\Setting::get('footer_menu_1', '');
    $footerMenu1 = is_string($footerMenu1) ? (json_decode($footerMenu1, true) ?: []) : $footerMenu1;
    if (empty($footerMenu1) || !isset($footerMenu1['title'])) {
        $footerMenu1 = ['title' => 'Explore Albania', 'items' => [
            ['label' => 'Day Tours', 'url' => '/tours/category/day-tours'],
            ['label' => 'Multi-Day Tour', 'url' => '/tours/category/multi-day-tours'],
            ['label' => 'Cross Country', 'url' => '/tours/category/cross-country-tours'],
            ['label' => 'Confirmed Group Tours', 'url' => '/tours'],
            ['label' => 'Private Group Tour Requests', 'url' => '/private-group-tour-requests'],
        ]];
    } else {
        $footerMenu1 = array_merge(['title' => 'Explore Albania', 'items' => []], $footerMenu1);
    }

    $footerMenu2 = \App\Models\Setting::get('footer_menu_2', '');
    $footerMenu2 = is_string($footerMenu2) ? (json_decode($footerMenu2, true) ?: []) : $footerMenu2;
    if (empty($footerMenu2) || !isset($footerMenu2['title'])) {
        $footerMenu2 = ['title' => 'Why Choose Us', 'items' => [
            ['label' => 'About Us', 'url' => '/about'],
            ['label' => 'Our Transport', 'url' => '#'],
            ['label' => 'Blog', 'url' => '/blog'],
            ['label' => 'Contact us', 'url' => '/contact'],
            ['label' => 'Terms & Cancellation Policy', 'url' => '/faq'],
        ]];
    } else {
        $footerMenu2 = array_merge(['title' => 'Why Choose Us', 'items' => []], $footerMenu2);
    }
@endphp
<footer>
    <div class="bg-white" style="background-color:#f5f5f5;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

                {{-- Column 1: Explore Albania (Footer Menu 1) --}}
                <div>
                    <h5 class="text-gray-900 font-bold text-sm mb-5">{{ $footerMenu1['title'] }}</h5>
                    @if(!empty($footerMenu1['items']))
                    <ul class="space-y-3 text-sm">
                        @foreach($footerMenu1['items'] as $item)
                        <li><a href="{{ str_starts_with($item['url'] ?? '', 'http') ? $item['url'] : url($item['url'] ?? '#') }}" class="text-gray-500 hover:text-brand-navy transition">{{ $item['label'] ?? '' }}</a></li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                {{-- Column 2: Why Choose Us (Footer Menu 2) --}}
                <div>
                    <h5 class="text-gray-900 font-bold text-sm mb-5">{{ $footerMenu2['title'] }}</h5>
                    @if(!empty($footerMenu2['items']))
                    <ul class="space-y-3 text-sm">
                        @foreach($footerMenu2['items'] as $item)
                        <li><a href="{{ str_starts_with($item['url'] ?? '', 'http') ? $item['url'] : url($item['url'] ?? '#') }}" class="text-gray-500 hover:text-brand-navy transition">{{ $item['label'] ?? '' }}</a></li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                {{-- Column 3: Get In Touch --}}
                <div>
                    <h5 class="text-gray-900 font-bold text-sm mb-5">Get In Touch</h5>
                    <ul class="space-y-3 text-sm">
                        @if($contactAddress)
                        <li>
                            <a href="https://maps.google.com/?q={{ urlencode($contactAddress) }}" target="_blank" rel="noopener" class="flex items-start gap-2.5 text-gray-500 hover:text-brand-navy transition">
                                <i class="fa-solid fa-location-dot text-xs mt-1 flex-shrink-0"></i>
                                <span>{{ $contactAddress }}</span>
                            </a>
                        </li>
                        @endif
                        @if($contactPhone)
                        <li>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone) }}" class="flex items-center gap-2.5 text-gray-500 hover:text-brand-navy transition">
                                <i class="fa-solid fa-phone text-xs"></i> {{ $contactPhone }}
                            </a>
                        </li>
                        @endif
                        @if($contactPhone2)
                        <li>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone2) }}" class="flex items-center gap-2.5 text-gray-500 hover:text-brand-navy transition">
                                <i class="fa-solid fa-phone text-xs"></i> {{ $contactPhone2 }}
                            </a>
                        </li>
                        @endif
                        @if($contactEmail)
                        <li>
                            <a href="mailto:{{ $contactEmail }}" class="flex items-center gap-2.5 text-gray-500 hover:text-brand-navy transition">
                                <i class="fa-solid fa-envelope text-xs"></i> {{ $contactEmail }}
                            </a>
                        </li>
                        @endif
                    </ul>
                    <div class="flex items-center gap-3 mt-5">
                        @if($instagramUrl)
                            <a href="{{ $instagramUrl }}" target="_blank" rel="noopener" class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center text-white hover:bg-brand-navy transition" aria-label="Instagram"><i class="fa-brands fa-instagram text-sm"></i></a>
                        @endif
                        @if($facebookUrl)
                            <a href="{{ $facebookUrl }}" target="_blank" rel="noopener" class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center text-white hover:bg-brand-navy transition" aria-label="Facebook"><i class="fa-brands fa-facebook-f text-sm"></i></a>
                        @endif
                    </div>
                </div>

                {{-- Column 4: Payments & Partnerships --}}
                <div>
                    <h5 class="text-gray-900 font-bold text-sm mb-4">Payments Accepted By</h5>
                    <div class="flex items-center gap-2 mb-6">
                        <span class="inline-flex items-center justify-center w-12 h-8 bg-[#003087] rounded">
                            <i class="fa-brands fa-paypal text-white text-sm"></i>
                        </span>
                        <span class="inline-flex items-center justify-center w-12 h-8 bg-[#1a1f71] rounded">
                            <i class="fa-brands fa-cc-visa text-white text-lg"></i>
                        </span>
                        <span class="inline-flex items-center justify-center w-12 h-8 bg-[#eb001b] rounded">
                            <i class="fa-brands fa-cc-mastercard text-white text-lg"></i>
                        </span>
                        <span class="inline-flex items-center justify-center w-12 h-8 bg-[#2e77bc] rounded">
                            <i class="fa-brands fa-cc-amex text-white text-lg"></i>
                        </span>
                    </div>

                    <h5 class="text-gray-900 font-bold text-sm mb-4">Partnership With</h5>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-10 h-10 bg-[#FF5533] rounded-full">
                            <i class="fa-solid fa-ticket text-white text-xs"></i>
                        </span>
                        <span class="inline-flex items-center justify-center w-10 h-10 bg-[#34E0A1] rounded-full">
                            <i class="fa-solid fa-comment-dots text-white text-xs"></i>
                        </span>
                        <span class="inline-flex items-center justify-center w-10 h-10 bg-gray-900 rounded-full">
                            <span class="text-white text-xs font-bold">t</span>
                        </span>
                        <span class="inline-flex items-center justify-center h-10 px-2 bg-gray-100 rounded-full">
                            <span class="text-gray-700 text-xs font-bold tracking-tight">tOurHQ</span>
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Bottom bar --}}
    <div class="bg-brand-navy">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-center text-sm text-white/80">&copy; Copyright {{ date('Y') }} by Albania Inbound</p>
        </div>
    </div>
</footer>
