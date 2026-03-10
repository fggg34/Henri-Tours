@extends('layouts.site')

@section('title', 'Amenities - ' . config('app.name'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Amenities</h1>
        <a href="{{ route('amenities.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-btn text-white text-sm font-medium rounded-md hover:bg-brand-btn-hover">Add amenity</a>
    </div>

    @if(session('success'))
        <p class="mb-4 text-sm text-green-600">{{ session('success') }}</p>
    @endif

    <p class="text-sm text-gray-500 mb-4">You can also manage amenities in the <a href="{{ url('/admin') }}" class="text-brand-navy hover:underline">Filament admin panel</a> under Locations → Amenities.</p>

    <ul class="space-y-2">
        @forelse($amenities as $amenity)
            <li class="flex items-center justify-between p-3 rounded-lg border border-gray-200 bg-white">
                <span class="flex items-center gap-3">
                    @if($amenity->icon)
                        <i class="{{ $amenity->icon }} text-brand-navy w-5" aria-hidden="true"></i>
                    @endif
                    <span class="font-medium text-gray-900">{{ $amenity->name }}</span>
                </span>
                <span class="flex items-center gap-2">
                    <a href="{{ route('amenities.edit', $amenity) }}" class="text-sm text-brand-navy hover:underline">Edit</a>
                    <form action="{{ route('amenities.destroy', $amenity) }}" method="POST" class="inline" onsubmit="return confirm('Delete this amenity?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
                    </form>
                </span>
            </li>
        @empty
            <li class="p-4 text-gray-500">No amenities yet. Run php artisan db:seed --class=AmenitySeeder or add one above.</li>
        @endforelse
    </ul>
</div>
@endsection
