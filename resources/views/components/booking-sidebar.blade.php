@props(['tour', 'defaultDate' => null, 'defaultGuests' => null])

@php
    $slug = $tour->slug;
    $maxGuests = max(1, (int) ($tour->max_group_size ?? 99));
    $priceUrl = route('tours.price', $slug);
    $datesUrl = route('tours.available-dates', $slug);
    $basePrice = (float)($tour->base_price ?? $tour->price ?? 0);
    $currency = '€';
@endphp

<div class="relative bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden" x-data="bookingSidebar({
    priceUrl: @js($priceUrl),
    datesUrl: @js($datesUrl),
    slug: @js($slug),
    maxGuests: {{ $maxGuests }},
    useCalendar: true,
    createUrl: @js(route('bookings.create', $slug)),
    currency: @js($currency),
    basePrice: {{ $basePrice }},
    initialDate: @js($defaultDate),
    initialGuests: {{ max(1, (int) ($defaultGuests ?? 1)) }},
})" x-init="init()">
    <div class="p-6">
        {{-- Price header --}}
        <div class="flex items-baseline gap-2 flex-wrap">
            <p class="text-3xl font-bold text-gray-900" x-show="!discountApplied" x-text="(pricePerPerson > 0 ? currency + ' ' + pricePerPerson.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') : currency + ' 0.00')"></p>
            <template x-if="discountApplied">
                <div class="flex items-baseline gap-2">
                    <p class="text-lg text-gray-500 line-through" x-text="(originalPricePerPerson > 0 ? currency + ' ' + originalPricePerPerson.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') : '')"></p>
                    <p class="text-3xl font-bold text-brand-btn" x-text="(pricePerPerson > 0 ? currency + ' ' + pricePerPerson.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') : currency + ' 0.00')"></p>
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-800" x-text="(discountApplied && discountApplied.label) ? discountApplied.label : 'Discount'"></span>
                </div>
            </template>
        </div>
        <p class="text-sm text-gray-500 mt-0.5">per person</p>

        @if($tour->pricingTiers && $tour->pricingTiers->isNotEmpty())
        <div class="mt-3 flex items-start gap-2 rounded-lg bg-blue-50 border border-blue-200 px-3 py-2">
            <span class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-blue-200 text-blue-700 mt-0.5">
                <i class="fa-solid fa-user-group text-xs"></i>
            </span>
            <p class="text-sm text-blue-800">Group discount available. Price per person drops when you book for more people.</p>
        </div>
        @endif

        {{-- Select Date --}}
        <div class="mt-5 block w-full">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Select Date <span class="text-red-500">*</span></label>
            <div class="relative block w-full">
                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 z-10 text-gray-400">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </span>
                <input type="text" x-ref="dateInput" placeholder="Select Date" readonly
                    class="block w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-10 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-navy focus:ring-1 focus:ring-blue-500">
                <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </span>
            </div>
            <div x-ref="calendarContainer" class="block w-full"></div>
        </div>

        {{-- Participants (clickable, opens popup) --}}
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Participants <span class="text-red-500">*</span></label>
            <div class="relative">
                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 z-10 text-gray-400">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </span>
                <button type="button" @click="participantsOpen = true"
                    class="w-full flex items-center rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-[10px] text-left text-sm text-gray-900 hover:border-gray-400 focus:border-brand-navy focus:ring-1 focus:ring-blue-500 transition-colors">
                    <span class="flex-1" x-text="guests + ' Adult' + (guests !== 1 ? 's' : '')"></span>
                    <svg class="h-5 w-5 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>
            </div>
        </div>

        {{-- Proceed to Booking --}}
        <form :action="createUrl + '?date=' + selectedDate + '&guests=' + guests" method="GET" class="mt-5">
            <input type="hidden" name="date" :value="selectedDate">
            <input type="hidden" name="guests" :value="guests">
            <button type="submit" :disabled="!selectedDate || loading"
                class="w-full py-3.5 bg-brand-btn text-white font-semibold rounded-lg hover:bg-brand-btn-hover transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-base">
                Proceed to Booking
            </button>
        </form>

        {{-- Price summary --}}
        <div class="mt-5 space-y-2 border-t border-gray-100 pt-5">
            <div class="flex justify-between text-sm items-center" x-show="discountApplied">
                <span class="text-gray-600">Original price</span>
                <span class="text-gray-500 line-through" x-text="originalPricePerPerson > 0 ? currency + ' ' + originalPricePerPerson.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') : '—'"></span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Price per person</span>
                <span class="text-gray-900 font-medium" x-text="total > 0 ? currency + ' ' + pricePerPerson.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') : '—'"></span>
            </div>
            <div class="flex justify-between text-md">
                <span class="text-gray-900 font-semibold">Total Price</span>
                <span class="text-brand-btn font-bold" x-text="total > 0 ? currency + ' ' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') : '—'"></span>
            </div>
        </div>

        {{-- Best price guarantee --}}
        <div class="mt-4 flex items-center gap-2 text-sm">
            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-red-100 text-brand-btn">
                <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
            </span>
            <span class="text-gray-600">Best price guarantee</span>
            <a href="#" class="text-brand-btn hover:underline font-medium">Learn More</a>
        </div>
    </div>

    {{-- Participants popup --}}
    <div x-show="participantsOpen"
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     @click.self="participantsOpen = false"
     style="display: none;">
    <div class="absolute inset-0 bg-black/50" @click="participantsOpen = false"></div>
    <div class="relative bg-white rounded-xl shadow-xl max-w-sm w-full p-6"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-gray-900">Participants</h3>
            <button type="button" @click="participantsOpen = false" class="rounded-full p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        {{-- Adults counter --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Adults</label>
            <div class="flex items-center gap-3 rounded-lg border border-gray-300 p-2">
                <button type="button" @click="if(guests > 1) guests--"
                    class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 font-medium transition">−</button>
                <span class="flex-1 text-center text-lg font-semibold text-gray-900" x-text="guests"></span>
                <button type="button" @click="if(guests < maxGuests) guests++"
                    class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 font-medium transition">+</button>
            </div>
        </div>
        {{-- Price in popup --}}
        <div class="mt-5 space-y-2 border-t border-gray-100 pt-5">
            <div class="flex justify-between text-sm items-center" x-show="discountApplied">
                <span class="text-gray-600">Original price</span>
                <span class="text-gray-500 line-through" x-text="originalPricePerPerson > 0 ? currency + ' ' + originalPricePerPerson.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') : '—'"></span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Price per person</span>
                <span class="text-gray-900 font-medium" x-text="currency + ' ' + pricePerPerson.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')"></span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-900 font-semibold">Total</span>
                <span class="text-brand-btn font-bold" x-text="currency + ' ' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')"></span>
            </div>
        </div>
        {{-- Done button --}}
        <button type="button" @click="participantsOpen = false"
            class="mt-5 w-full py-3.5 bg-brand-btn text-white font-semibold rounded-lg hover:bg-brand-btn-hover transition-colors">
            Done
        </button>
    </div>
    </div>
</div>
