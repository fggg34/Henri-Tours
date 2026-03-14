<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">

            {{-- Header --}}
            <div class="mb-8 lg:mb-10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">My Trips</h1>
                        <p class="mt-1 text-gray-600">Manage your bookings and saved tours</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-brand-navy transition-colors">
                        <span class="w-9 h-9 rounded-full bg-white border border-gray-200 flex items-center justify-center">
                            <i class="fa-solid fa-user text-gray-500 text-sm"></i>
                        </span>
                        Account settings
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-8 flex items-center gap-3 px-4 py-3 bg-blue-50 border border-blue-200 text-brand-navy rounded-xl">
                    <i class="fa-solid fa-circle-check text-brand-navy text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Stats --}}
            <div class="grid grid-cols-2 gap-4 mb-8 lg:mb-10">
                <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                            <i class="fa-solid fa-calendar-check text-brand-navy text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $activeBookingsCount ?? 0 }}</p>
                            <p class="text-sm text-gray-500">Bookings</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                            <i class="fa-solid fa-heart text-amber-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $wishlistTours->count() }}</p>
                            <p class="text-sm text-gray-500">Saved tours</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-8 lg:space-y-10">
                {{-- My bookings --}}
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">My bookings</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Your upcoming and past reservations</p>
                        </div>
                        <a href="{{ localized_route('tours.index') }}" class="text-sm font-medium text-brand-navy hover:text-brand-navy transition-colors hidden sm:inline-flex items-center gap-1">
                            Browse tours
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        @if($bookings->isEmpty())
                            <div class="p-12 text-center">
                                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-calendar-xmark text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-gray-600 font-medium">No bookings yet</p>
                                <p class="text-sm text-gray-500 mt-1 mb-6">Discover amazing tours and start planning your next adventure.</p>
                                <a href="{{ localized_route('tours.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-btn hover:bg-brand-btn-hover text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fa-solid fa-compass"></i>
                                    Browse tours
                                </a>
                            </div>
                        @else
                            <ul class="divide-y divide-gray-100">
                                @foreach($bookings as $booking)
                                    @php
                                        $bookingDate = $booking->tourDate?->date ?? $booking->booking_date;
                                        $isPast = $bookingDate && $bookingDate->isPast();
                                        $currency = $booking->currency ?: '€';
                                    @endphp
                                    <li class="group">
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-6 hover:bg-gray-50/50 transition-colors">
                                            <div class="flex-1 min-w-0">
                                                <a href="{{ localized_route('bookings.confirmation', ['token' => $booking->confirmation_token]) }}" class="font-semibold text-gray-900 hover:text-brand-navy transition line-clamp-2">
                                                    {{ $booking->tour->title }}
                                                </a>
                                                <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-500">
                                                    <span class="flex items-center gap-1.5">
                                                        <i class="fa-regular fa-calendar text-gray-400 text-xs"></i>
                                                        {{ $bookingDate?->format('M j, Y') ?? '—' }}
                                                    </span>
                                                    <span class="flex items-center gap-1.5">
                                                        <i class="fa-solid fa-users text-gray-400 text-xs"></i>
                                                        {{ $booking->guest_count }} {{ Str::plural('guest', $booking->guest_count) }}
                                                    </span>
                                                    <span class="font-medium text-gray-700">{{ $currency }}{{ number_format($booking->total_amount, 2) }}</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3 flex-shrink-0">
                                                <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg
                                                    @if($booking->status === 'confirmed') bg-emerald-50 text-emerald-700
                                                    @elseif($booking->status === 'cancelled') bg-gray-100 text-gray-500
                                                    @else bg-blue-50 text-brand-navy @endif">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                                <a href="{{ localized_route('bookings.confirmation', ['token' => $booking->confirmation_token]) }}" class="text-sm font-medium text-brand-navy hover:text-brand-navy transition">
                                                    View details
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            @if($bookings->hasPages())
                                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">{{ $bookings->links() }}</div>
                            @endif
                        @endif
                    </div>
                    @if(!$bookings->isEmpty())
                        <div class="mt-4 text-center sm:hidden">
                            <a href="{{ localized_route('tours.index') }}" class="text-sm font-medium text-brand-navy hover:text-brand-navy">Browse more tours</a>
                        </div>
                    @endif
                </section>

                {{-- Saved tours --}}
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Saved tours</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Tours you've added to your wishlist</p>
                        </div>
                        <a href="{{ localized_route('tours.index') }}" class="text-sm font-medium text-brand-navy hover:text-brand-navy transition-colors hidden sm:inline-flex items-center gap-1">
                            Explore tours
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        @if($wishlistTours->isEmpty())
                            <div class="p-12 text-center">
                                <div class="w-16 h-16 rounded-2xl bg-amber-50 flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-regular fa-heart text-amber-500 text-2xl"></i>
                                </div>
                                <p class="text-gray-600 font-medium">No saved tours yet</p>
                                <p class="text-sm text-gray-500 mt-1 mb-6">Save tours you love and they'll appear here for easy access.</p>
                                <a href="{{ localized_route('tours.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-btn hover:bg-brand-btn-hover text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fa-solid fa-compass"></i>
                                    Explore tours
                                </a>
                            </div>
                        @else
                            <div class="p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($wishlistTours as $tour)
                                        <div class="group relative p-4 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50/30 transition-all">
                                            <form action="{{ route('wishlist.destroy', $tour) }}" method="POST" class="absolute top-4 right-4 z-10">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-9 h-9 rounded-full bg-white/95 shadow-sm flex items-center justify-center text-rose-500 hover:bg-rose-50 transition-colors" title="Remove from wishlist">
                                                    <i class="fa-solid fa-heart text-sm"></i>
                                                </button>
                                            </form>
                                            <a href="{{ localized_route('tours.show', ['slug' => $tour->slug]) }}" class="block">
                                                @php
                                                    $firstImg = $tour->images->first();
                                                    $imageUrl = $firstImg?->url ?? 'https://placehold.co/400x300/e5e7eb/6b7280?text=Tour';
                                                @endphp
                                                <div class="aspect-[4/3] rounded-lg overflow-hidden bg-gray-100 mb-3">
                                                    <img src="{{ $imageUrl }}" alt="{{ $tour->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                                </div>
                                                <h3 class="font-semibold text-gray-900 group-hover:text-brand-navy transition line-clamp-2 pr-10">{{ $tour->title }}</h3>
                                                @if($tour->price)
                                                    <p class="mt-1 text-sm font-medium text-gray-600">From €{{ number_format($tour->price, 0) }}</p>
                                                @endif
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    @if(!$wishlistTours->isEmpty())
                        <div class="mt-4 text-center sm:hidden">
                            <a href="{{ localized_route('tours.index') }}" class="text-sm font-medium text-brand-navy hover:text-brand-navy">Explore more tours</a>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
