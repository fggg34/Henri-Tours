@extends('layouts.site')

@section('title', 'Confirmed Departures & Discounted Rates - ' . config('app.name'))
@section('description', 'Browse our confirmed group tour departures with guaranteed discounted rates. Book your Albanian adventure today.')

@section('content')
{{-- ========== HERO SECTION ========== --}}
<section class="relative w-full overflow-hidden" style="min-height: 480px;">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1551632811-561732d1e306?w=1920&q=80');"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-black/60"></div>
    <div class="relative z-10 flex flex-col items-center justify-center text-center px-4 sm:px-6 lg:px-8 py-20" style="min-height: 480px;">
        <h1 class="text-3xl md:text-4xl lg:text-[2.6rem] font-bold text-white leading-tight max-w-4xl">
            Organized Group Tours & Confirmed Departures – Discounted Rates in All Dates
        </h1>
        <p class="mt-4 text-base md:text-lg text-white/90 max-w-2xl leading-relaxed">
            Here we have listed all our confirmed dates with guaranteed departures – Take advantage of special discounts available only on these dates!
        </p>
        <div class="flex flex-wrap items-center justify-center gap-8 mt-8">
            <div class="flex items-center gap-2">
                <span class="text-amber-400 text-2xl">🏆</span>
                <span class="text-white font-semibold text-sm md:text-base">Albania's leading<br>Travel agency</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-amber-400 text-2xl">🏆</span>
                <span class="text-white font-semibold text-sm md:text-base">Best price<br>guarantee</span>
            </div>
        </div>
    </div>
</section>

{{-- Trust Bar --}}
<div class="bg-brand-trust text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 divide-x divide-white/10">
            <div class="flex items-center gap-3 py-4 px-3 lg:px-5">
                <span class="text-2xl">🏷️</span>
                <p class="text-sm font-semibold">Book with only a 10% deposit</p>
            </div>
            <div class="flex items-center gap-3 py-4 px-3 lg:px-5">
                <span class="text-2xl">📋</span>
                <p class="text-sm font-semibold">Easy Booking & Cancellation</p>
            </div>
            <div class="flex items-center gap-3 py-4 px-3 lg:px-5">
                <span class="text-2xl">📅</span>
                <p class="text-sm font-semibold">Flexible & Confirmed Departures</p>
            </div>
            <div class="flex items-center gap-3 py-4 px-3 lg:px-5">
                <span class="text-2xl">✅</span>
                <p class="text-sm font-semibold">Trusted by Travelers</p>
            </div>
        </div>
    </div>
</div>

@if($tours->isEmpty())
<div class="max-w-5xl mx-auto px-4 py-20 text-center">
    <i class="fa-solid fa-calendar-xmark text-5xl text-gray-300 mb-4"></i>
    <h2 class="text-2xl font-bold text-gray-700 mb-2">No confirmed departures yet</h2>
    <p class="text-gray-500 mb-6">Check back later for new discounted departure dates.</p>
    <a href="{{ route('tours.index') }}" class="inline-flex px-6 py-3 bg-brand-btn hover:bg-brand-btn-hover text-white font-medium rounded-lg transition-colors">Browse all tours</a>
</div>
@else

{{-- ========== TOUR SELECTOR + DATES ========== --}}
<section class="bg-gray-50 py-12 md:py-16" x-data="confirmedDepartures()" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section heading --}}
        <div class="mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Select Tour</h2>
        </div>

        {{-- Tour Cards Swiper --}}
        <div class="relative">
            @if($tours->count() > 3)
            <div class="hidden md:flex items-center justify-end gap-2 mb-4">
                <button type="button" class="departures-prev w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-brand-navy hover:border-brand-navy transition-colors">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </button>
                <button type="button" class="departures-next w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-brand-navy hover:border-brand-navy transition-colors">
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </button>
            </div>
            @endif

            <div class="swiper departures-swiper overflow-visible" style="padding: 4px; margin: -4px;">
                <div class="swiper-wrapper">
                    @foreach($tours as $idx => $tour)
                    @php
                        $firstImg = $tour->images->first();
                        $imageUrl = $firstImg?->url ?? 'https://placehold.co/600x400/e2e8f0/64748b?text=Tour';
                        $categoryName = $tour->category?->name;
                        $shortDesc = $tour->short_description ?? \Illuminate\Support\Str::limit(strip_tags($tour->description), 120);
                    @endphp
                    <div class="swiper-slide" style="height: auto;">
                        <div @click="selectTour({{ $idx }})"
                             :class="activeTour === {{ $idx }} ? 'ring-2 ring-brand-btn ring-offset-2' : 'hover:shadow-lg'"
                             class="cursor-pointer bg-white rounded-lg overflow-hidden border border-gray-200 transition-all h-full flex flex-col">
                            <div class="relative overflow-hidden aspect-[4/3]">
                                <img src="{{ $imageUrl }}" alt="{{ $tour->title }}" class="w-full h-full object-cover" loading="lazy">
                                @if($categoryName)
                                    <span class="absolute bottom-3 left-3 inline-flex items-center px-3 py-1 text-xs font-semibold rounded bg-white/90 text-gray-800 backdrop-blur-sm">{{ $categoryName }}</span>
                                @endif
                            </div>
                            <div class="p-4 flex flex-col flex-1">
                                <h3 class="text-base font-bold text-gray-900 leading-snug mb-2 line-clamp-2">{{ $tour->title }}</h3>
                                <p class="text-sm text-gray-500 line-clamp-3 mb-4 flex-1">{{ $shortDesc }}</p>
                                <div class="flex items-center gap-2 mt-auto">
                                    <span class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded cursor-pointer">Confirmed Dates</span>
                                    <a href="{{ route('tours.show', $tour->slug) }}" class="inline-flex items-center px-3 py-1.5 bg-brand-navy text-white text-xs font-bold rounded hover:bg-brand-navy-light transition-colors" @click.stop>View Tour</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Dates Table --}}
        <div id="dates-section" class="mt-10 scroll-mt-8">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                {{-- Table header --}}
                <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 bg-gray-50 border-b border-gray-200 text-sm font-semibold text-gray-600">
                    <div class="col-span-3">Starting</div>
                    <div class="col-span-3">Ending</div>
                    <div class="col-span-3 text-center"></div>
                    <div class="col-span-3 text-right">Prices from</div>
                </div>

                {{-- Date rows - one per tour, toggled by activeTour --}}
                @foreach($toursData as $tourIdx => $tourItem)
                    <div x-show="activeTour === {{ $tourIdx }}" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        @foreach($tourItem['dates'] as $dateIdx => $date)
                        <div @click="selectDate({{ $tourIdx }}, {{ $dateIdx }})"
                             :class="activeTour === {{ $tourIdx }} && activeDate === {{ $dateIdx }} ? 'bg-blue-50 border-l-4 border-l-brand-navy' : 'border-l-4 border-l-transparent hover:bg-gray-50'"
                             class="grid grid-cols-1 md:grid-cols-12 gap-2 md:gap-4 px-4 md:px-6 py-5 border-b border-gray-100 cursor-pointer transition-colors">
                            {{-- Start date --}}
                            <div class="md:col-span-3">
                                <span class="md:hidden text-xs font-medium text-gray-400 uppercase">Starting</span>
                                <p class="text-sm text-gray-900">
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($date['start'])->format('l d') }}</span>
                                    <span class="font-bold ml-1">{{ \Carbon\Carbon::parse($date['start'])->format('F Y') }}</span>
                                </p>
                            </div>
                            {{-- End date --}}
                            <div class="md:col-span-3">
                                <span class="md:hidden text-xs font-medium text-gray-400 uppercase">Ending</span>
                                <p class="text-sm text-gray-900">
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($date['end'])->format('l d') }}</span>
                                    <span class="font-bold ml-1">{{ \Carbon\Carbon::parse($date['end'])->format('F Y') }}</span>
                                </p>
                            </div>
                            {{-- Status --}}
                            <div class="md:col-span-3 flex flex-wrap items-center gap-2 md:justify-center">
                                <span class="inline-flex items-center px-2.5 py-1 bg-red-500 text-white text-xs font-bold rounded">Sale</span>
                                <span class="text-sm text-green-700 font-medium">Available</span>
                                <span class="text-xs text-gray-400">On request</span>
                            </div>
                            {{-- Price --}}
                            <div class="md:col-span-3 text-left md:text-right">
                                <span class="md:hidden text-xs font-medium text-gray-400 uppercase">Prices from</span>
                                <p class="text-sm text-gray-400 line-through">{{ $date['original'] }}</p>
                                <p class="text-lg font-bold text-gray-900">{{ $date['price'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ========== INQUIRY FORM ========== --}}
    <div id="inquiry-form" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 scroll-mt-8">
        <div class="bg-slate-50 rounded-2xl border border-gray-200 p-6 md:p-10 shadow-sm">
            <h2 class="text-xl md:text-2xl font-bold text-brand-navy mb-1">Tour Booking & Info Inquiry</h2>
            <div x-show="selectedTourName" class="mb-6">
                <p class="text-sm text-gray-500 mt-1">
                    Tour: <span class="font-semibold text-gray-800" x-text="selectedTourName"></span>
                    <template x-if="selectedDateLabel">
                        <span> &middot; <span class="font-semibold text-gray-800" x-text="selectedDateLabel"></span></span>
                    </template>
                </p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 text-green-800 rounded-xl border border-green-100 flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('confirmed-departures.store') }}" method="POST" class="space-y-8">
                @csrf
                <input type="hidden" name="tour_id" :value="tourId">
                <input type="hidden" name="discount_id" :value="discountId">

                {{-- Personal Details --}}
                <div>
                    <h3 class="text-base font-bold text-gray-900 mb-4">Personal Details:</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                            <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                            @error('full_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1.5">Date of birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                            @error('date_of_birth')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="inquiry_email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                            <input type="email" name="email" id="inquiry_email" value="{{ old('email') }}" required
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="inquiry_phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone number</label>
                            <input type="tel" name="phone" id="inquiry_phone" value="{{ old('phone') }}" placeholder="201-555-0123"
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900"
                                data-initial-phone="{{ e(old('phone', '')) }}">
                            @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Contact Details --}}
                <div>
                    <h3 class="text-base font-bold text-gray-900 mb-4">Contact Details:</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="inquiry_address" class="block text-sm font-medium text-gray-700 mb-1.5">Address</label>
                            <input type="text" name="address" id="inquiry_address" value="{{ old('address') }}"
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                            @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="inquiry_city" class="block text-sm font-medium text-gray-700 mb-1.5">City</label>
                            <input type="text" name="city" id="inquiry_city" value="{{ old('city') }}"
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                            @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="inquiry_country" class="block text-sm font-medium text-gray-700 mb-1.5">Country</label>
                            <input type="text" name="country" id="inquiry_country" value="{{ old('country') }}"
                                class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                            @error('country')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Room Options --}}
                <div>
                    <label for="room_option" class="block text-sm font-bold text-gray-900 mb-1.5">Room Options:</label>
                    <select name="room_option" id="room_option"
                        class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900">
                        <option value="individual">Individual Room (Additional cost)</option>
                        <option value="shared_double">Shared / Double Room</option>
                        <option value="triple">Triple Room</option>
                    </select>
                </div>

                {{-- Message --}}
                <div>
                    <label for="inquiry_message" class="block text-sm font-bold text-gray-900 mb-1.5">Your message</label>
                    <textarea name="message" id="inquiry_message" rows="5"
                        class="w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-navy focus:ring-1 focus:ring-blue-500 text-gray-900 resize-y">{{ old('message') }}</textarea>
                    @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="text-center">
                    <button type="submit" class="px-10 py-3.5 bg-brand-navy hover:bg-brand-navy-light text-white font-semibold rounded-lg transition-colors text-base">
                        SUBMIT
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@endif
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@26/build/css/intlTelInput.min.css">
<style>
.iti.iti--allow-dropdown.iti--show-flags.iti--inline-dropdown { width: 100%; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@26/build/js/intlTelInput.min.js"></script>
<script>
function confirmedDepartures() {
    return {
        activeTour: 0,
        activeDate: null,
        tours: {!! $toursJson ?? '[]' !!},
        get tourId() {
            return this.tours[this.activeTour]?.id ?? '';
        },
        get discountId() {
            if (this.activeDate === null) return '';
            var dates = this.tours[this.activeTour]?.dates ?? [];
            return dates[this.activeDate]?.id ?? '';
        },
        get selectedTourName() {
            return this.tours[this.activeTour]?.title ?? '';
        },
        get selectedDateLabel() {
            if (this.activeDate === null) return '';
            var d = this.tours[this.activeTour]?.dates?.[this.activeDate];
            return d ? d.start_label + ' – ' + d.end_label : '';
        },
        selectTour(idx) {
            this.activeTour = idx;
            this.activeDate = null;
            this.$nextTick(() => {
                var el = document.getElementById('dates-section');
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        },
        selectDate(tourIdx, dateIdx) {
            this.activeTour = tourIdx;
            this.activeDate = dateIdx;
            this.$nextTick(() => {
                var el = document.getElementById('inquiry-form');
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        },
        init() {
            this.$nextTick(() => {
                if (window.Swiper && document.querySelector('.departures-swiper')) {
                    new window.Swiper('.departures-swiper', {
                        modules: [window.SwiperNavigation],
                        slidesPerView: 1.15,
                        spaceBetween: 16,
                        navigation: { prevEl: '.departures-prev', nextEl: '.departures-next' },
                        breakpoints: {
                            640: { slidesPerView: 2.2, spaceBetween: 16 },
                            1024: { slidesPerView: 3.2, spaceBetween: 20 },
                            1280: { slidesPerView: 4, spaceBetween: 20 },
                        },
                    });
                }

                var phoneInput = document.getElementById('inquiry_phone');
                if (phoneInput && window.intlTelInput) {
                    var initialNumber = phoneInput.getAttribute('data-initial-phone') || '';
                    var iti = window.intlTelInput(phoneInput, {
                        initialCountry: 'al',
                        utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@26/build/js/utils.js'
                    });
                    if (initialNumber) iti.setNumber(initialNumber);
                    var form = phoneInput.closest('form');
                    if (form) {
                        form.addEventListener('submit', function () {
                            try {
                                var data = iti.getSelectedCountryData();
                                var dialCode = data && data.dialCode ? '+' + data.dialCode : '';
                                var digits = (phoneInput.value || '').replace(/\D/g, '');
                                if (dialCode && digits) phoneInput.value = dialCode + digits;
                            } catch (e) {}
                        });
                    }
                }
            });
        }
    };
}
</script>
@endpush
