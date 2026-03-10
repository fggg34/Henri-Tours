@props(['tour'])

@php
    $basePrice = (float)($tour->base_price ?? $tour->price ?? 0);
    $currency = '€';
    $formattedPrice = $basePrice > 0 ? number_format($basePrice, 0) : '0';
@endphp

<div x-data="mobileBookingBar()"
     x-init="init()"
     x-show="visible"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="translate-y-full opacity-0"
     x-transition:enter-end="translate-y-0 opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="translate-y-0 opacity-100"
     x-transition:leave-end="translate-y-full opacity-0"
     class="fixed bottom-0 left-0 right-0 z-40 lg:hidden bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 sm:py-4">
        <div class="flex items-center justify-between gap-4">
            <div class="flex flex-col min-w-0">
                <span class="text-xs text-gray-500">From</span>
                <span class="text-lg font-bold text-brand-navy">{{ $currency }} {{ $formattedPrice }} <span class="text-xs font-normal text-gray-500">/ person</span></span>
            </div>
            <button type="button"
                    @click="scrollToBooking()"
                    class="flex-shrink-0 py-3 px-5 bg-brand-btn text-white font-semibold rounded-lg hover:bg-brand-btn-hover transition-colors text-sm sm:text-base">
                Proceed to Booking
            </button>
        </div>
        <div class="mt-2 flex items-center justify-center gap-2 text-xs text-gray-500">
            <span class="flex h-4 w-4 items-center justify-center rounded-full bg-blue-100 text-brand-navy">
                <svg class="h-2.5 w-2.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
            </span>
            <span>Best price guarantee</span>
        </div>
    </div>
</div>
