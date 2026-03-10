@extends('layouts.site')

@section('title', 'All Cities - ' . config('app.name'))
@section('description', 'Explore cities and find hotels and tours.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center gap-1.5">
            <li>
                <a href="{{ route('home') }}" class="text-brand-navy hover:text-brand-navy transition">Home</a>
            </li>
            <li class="flex items-center gap-1.5" aria-hidden="true">
                <span>&gt;</span>
            </li>
            <li class="text-gray-700" aria-current="page">All Cities</li>
        </ol>
    </nav>

    <h1 class="text-2xl font-bold text-gray-900 mb-6">All Cities</h1>

    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($cities as $city)
            <a href="{{ route('cities.show', $city->slug) }}" class="group block rounded-xl overflow-hidden bg-gray-200 shadow-sm hover:shadow-md transition-shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <div class="aspect-[4/3] relative overflow-hidden">
                    @if($city->city_image_url)
                        <img src="{{ $city->city_image_url }}" alt="{{ $city->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full bg-gray-300 flex items-center justify-center">
                            <span class="text-gray-500 text-lg font-medium">{{ $city->name }}</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                        <h2 class="text-xl font-bold" style="color: #fff !important;">{{ $city->name }}</h2>
                        <p class="text-sm text-white/90 mt-1">
                            {{ $city->hotels_count }} {{ Str::plural('Hotel', $city->hotels_count) }}
                            @if($city->tours_count > 0)
                                · {{ $city->tours_count }} {{ Str::plural('Tour', $city->tours_count) }}
                            @endif
                        </p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    @if($cities->isEmpty())
        <p class="text-gray-600">No cities yet. Add cities in the admin panel.</p>
    @endif
</div>
@endsection
