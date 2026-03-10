@extends('layouts.site')

@section('title', 'Our Packages - ' . config('app.name'))
@section('description', 'Browse our tour packages and discover amazing experiences.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8">Our Packages</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @forelse($packages as $package)
        @php
            $imageUrl = $package->image_url ? asset($package->image_url) : 'https://placehold.co/400x300/e5e7eb/6b7280?text=' . urlencode($package->title);
            $linkUrl = $package->instagram_post_url ?: '#';
        @endphp
        <a href="{{ $linkUrl }}" target="{{ $package->instagram_post_url ? '_blank' : '_self' }}" rel="{{ $package->instagram_post_url ? 'noopener noreferrer' : '' }}"
           class="group block rounded-xl overflow-hidden bg-gray-200 border border-gray-200 hover:border-blue-300 hover:shadow-lg transition-all duration-300">
            <img src="{{ $imageUrl }}" alt="{{ $package->title }}"
                 class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
        </a>
        @empty
            <p class="col-span-full text-gray-500 text-center py-12">No packages yet.</p>
        @endforelse
    </div>
</div>
@endsection
