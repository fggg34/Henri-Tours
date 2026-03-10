{{--
    Blade snippet: Hotel form (create/edit) – Amenities as checkboxes with icons.
    Use in a form that submits to a controller that syncs amenity IDs to the hotel.

    Required:
    - $amenities: Collection of App\Models\Amenity (e.g. Amenity::orderBy('sort_order')->orderBy('name')->get())
    - $selectedIds: array of selected amenity IDs (e.g. $hotel->amenities->pluck('id')->toArray() for edit)

    Form field name: amenities[] (so request('amenities') is an array of IDs).
    In controller: $hotel->amenities()->sync($request->validated('amenities', []));
--}}
<section class="mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-3">Amenities / Facilities</h3>
    <p class="text-sm text-gray-500 mb-4">Select the amenities this hotel offers.</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
        @foreach($amenities as $amenity)
            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-brand-navy hover:bg-blue-50/30 cursor-pointer transition">
                <input type="checkbox"
                       name="amenities[]"
                       value="{{ $amenity->id }}"
                       {{ in_array($amenity->id, $selectedIds) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-brand-navy focus:ring-blue-500">
                @if($amenity->icon)
                    <i class="{{ $amenity->icon }} w-5 h-5 text-brand-navy flex-shrink-0" aria-hidden="true"></i>
                @else
                    <span class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-3 h-3 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </span>
                @endif
                <span class="text-gray-700 text-sm">{{ $amenity->name }}</span>
            </label>
        @endforeach
    </div>
    @error('amenities')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</section>
