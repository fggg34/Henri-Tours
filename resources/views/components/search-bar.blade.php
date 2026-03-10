@props(['action' => route('tours.index'), 'placeholder' => 'Search tours...'])

<form action="{{ $action }}" method="GET" class="flex flex-col sm:flex-row gap-2 max-w-2xl mx-auto">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ $placeholder }}" class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-brand-navy focus:ring-blue-500 px-4 py-3">
    <button type="submit" class="px-6 py-3 bg-brand-btn text-white font-medium rounded-lg hover:bg-brand-btn-hover transition">
        Search
    </button>
</form>
