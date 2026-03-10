@php
    $siteName = \App\Models\Setting::get('site_name', config('app.name'));
    $footerLogo = \App\Models\Setting::get('footer_logo', '') ?: \App\Models\Setting::get('site_logo', '');
    $footerLogoUrl = $footerLogo ? \Illuminate\Support\Facades\Storage::disk('public')->url($footerLogo) : null;
    $contactEmail = \App\Models\Setting::get('contact_email', 'info@albaniainbound.com');
    $contactPhone = \App\Models\Setting::get('contact_phone', '+355 69 238 0166');
    $contactPhone2 = \App\Models\Setting::get('contact_phone_2', '+355 69 700 3355');
    $contactAddress = \App\Models\Setting::get('contact_address', 'St. Ymer Kurti, Tirane 1019, Albania');
    $instagramUrl = \App\Models\Setting::get('instagram_url', '');
    $facebookUrl = \App\Models\Setting::get('facebook_url', '');
    $tiktokUrl = \App\Models\Setting::get('tiktok_url', '');
    $youtubeUrl = \App\Models\Setting::get('youtube_url', '');
@endphp
<footer class="bg-brand-footer text-gray-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

            {{-- Column 1: Brand + Social --}}
            <div>
                @if($footerLogoUrl)
                    <a href="{{ route('home') }}" class="inline-block mb-5">
                        <img src="{{ $footerLogoUrl }}" alt="{{ $siteName }}" class="h-10 w-auto brightness-0 invert">
                    </a>
                @else
                    <a href="{{ route('home') }}" class="inline-block mb-5 text-xl font-bold text-white">{{ $siteName }}</a>
                @endif
                <p class="text-sm text-gray-400 leading-relaxed mb-5">Trusted experts for vacation packages, day trips, group tours and activities in Albania!</p>
                <div class="flex items-center gap-4">
                    @if($instagramUrl)
                        <a href="{{ $instagramUrl }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-gray-300 hover:bg-white/20 hover:text-white transition" aria-label="Instagram"><i class="fa-brands fa-instagram text-lg"></i></a>
                    @endif
                    @if($facebookUrl)
                        <a href="{{ $facebookUrl }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-gray-300 hover:bg-white/20 hover:text-white transition" aria-label="Facebook"><i class="fa-brands fa-facebook-f text-lg"></i></a>
                    @endif
                    @if($tiktokUrl)
                        <a href="{{ $tiktokUrl }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-gray-300 hover:bg-white/20 hover:text-white transition" aria-label="TikTok"><i class="fa-brands fa-tiktok text-lg"></i></a>
                    @endif
                    @if($youtubeUrl)
                        <a href="{{ $youtubeUrl }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-gray-300 hover:bg-white/20 hover:text-white transition" aria-label="YouTube"><i class="fa-brands fa-youtube text-lg"></i></a>
                    @endif
                </div>
            </div>

            {{-- Column 2: Explore Albania --}}
            <div>
                <h5 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Explore Albania</h5>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('tours.index') }}" class="text-gray-400 hover:text-white transition">Day Tours</a></li>
                    <li><a href="{{ route('tours.index', ['category' => 'multi-day-tours']) }}" class="text-gray-400 hover:text-white transition">Multi-Day Tour</a></li>
                    <li><a href="{{ route('tours.index', ['category' => 'cross-country']) }}" class="text-gray-400 hover:text-white transition">Cross Country</a></li>
                    <li><a href="{{ route('tours.index') }}" class="text-gray-400 hover:text-white transition">Confirmed Group Tours</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white transition">Private Group Tour Requests</a></li>
                </ul>
            </div>

            {{-- Column 3: Why Choose Us --}}
            <div>
                <h5 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Why Choose Us</h5>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white transition">About Us</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-gray-400 hover:text-white transition">Blog</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white transition">Contact us</a></li>
                    <li><a href="{{ route('faq') }}" class="text-gray-400 hover:text-white transition">Terms & Cancellation Policy</a></li>
                </ul>
            </div>

            {{-- Column 4: Get In Touch --}}
            <div>
                <h5 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Get In Touch</h5>
                <ul class="space-y-3 text-sm">
                    @if($contactAddress)
                    <li>
                        <a href="https://maps.google.com/?q={{ urlencode($contactAddress) }}" target="_blank" rel="noopener" class="flex items-start gap-2.5 text-gray-400 hover:text-white transition">
                            <i class="fa-solid fa-location-dot text-xs mt-1 text-gray-500 flex-shrink-0"></i>
                            <span>{{ $contactAddress }}</span>
                        </a>
                    </li>
                    @endif
                    @if($contactPhone)
                    <li>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone) }}" class="flex items-center gap-2.5 text-gray-400 hover:text-white transition">
                            <i class="fa-solid fa-phone text-xs text-gray-500"></i> {{ $contactPhone }}
                        </a>
                    </li>
                    @endif
                    @if($contactPhone2)
                    <li>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone2) }}" class="flex items-center gap-2.5 text-gray-400 hover:text-white transition">
                            <i class="fa-solid fa-phone text-xs text-gray-500"></i> {{ $contactPhone2 }}
                        </a>
                    </li>
                    @endif
                    @if($contactEmail)
                    <li>
                        <a href="mailto:{{ $contactEmail }}" class="flex items-center gap-2.5 text-gray-400 hover:text-white transition">
                            <i class="fa-solid fa-envelope text-xs text-gray-500"></i> {{ $contactEmail }}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="border-t border-gray-700/50 mt-12 pt-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-500">Payments accepted:</span>
                    <div class="flex items-center gap-2">
                        <i class="fa-brands fa-cc-visa text-2xl text-gray-400"></i>
                        <i class="fa-brands fa-cc-mastercard text-2xl text-gray-400"></i>
                        <i class="fa-brands fa-cc-paypal text-2xl text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
