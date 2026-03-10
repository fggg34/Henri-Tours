@extends('layouts.site')

@section('title', 'Add amenity - ' . config('app.name'))

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('amenities.index') }}" class="text-sm text-brand-navy hover:underline mb-4 inline-block">Back to amenities</a>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Add amenity</h1>

    <form action="{{ route('amenities.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="255" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 focus:border-brand-navy focus:ring-blue-500">
            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="icon" class="block text-sm font-medium text-gray-700">Icon (FontAwesome class)</label>
            <input type="text" name="icon" id="icon" value="{{ old('icon') }}" placeholder="e.g. fa-solid fa-wifi" maxlength="255" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 focus:border-brand-navy focus:ring-blue-500">
            @error('icon')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-brand-btn text-white text-sm font-medium rounded-md hover:bg-brand-btn-hover">Create</button>
            <a href="{{ route('amenities.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 text-sm hover:bg-gray-50">Cancel</a>
        </div>
    </form>
</div>
@endsection
