@extends('layouts.site')

@section('title', 'Blog - ' . config('app.name'))
@section('description', 'Travel tips, destination guides and news.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <header class="text-center mb-12">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Our Blog</h1>
        <p class="text-gray-500 text-lg">Discover Albania beyond the usual routes</p>
    </header>

    @if($mainPost ?? null)
    {{-- Featured section: 2 columns - left 1 large, right 4 small in 2x2 --}}
    <section class="mb-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
            <div class="min-h-0 flex">
                <x-blog-card-featured :post="$mainPost" variant="large" />
            </div>
            @if(($sidePosts ?? collect())->isNotEmpty())
            <div class="grid grid-cols-2 grid-rows-2 gap-4 min-h-0 content-stretch">
                @foreach($sidePosts as $post)
                <div class="min-h-0 flex">
                    <x-blog-card-featured :post="$post" variant="small" />
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>
    @endif

    @forelse($postsByCategory as $section)
        <section class="mb-12" data-blog-category="{{ $section['slug'] }}" data-blog-offset="12">
            <h2 class="text-xl font-bold text-gray-900 mb-5 pb-2 border-b border-gray-200">
                @if($section['slug'] === 'uncategorized')
                    <a href="{{ route('blog.category.uncategorized') }}" class="hover:text-brand-navy transition-colors">{{ $section['name'] }}</a>
                @else
                    <a href="{{ route('blog.category', $section['slug']) }}" class="hover:text-brand-navy transition-colors">{{ $section['name'] }}</a>
                @endif
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 blog-category-grid">
                @foreach($section['posts'] as $post)
                    <x-blog-card :post="$post" />
                @endforeach
            </div>
            @if($section['hasMore'] ?? false)
            <div class="mt-6 text-center">
                <button type="button" class="blog-load-more px-6 py-3 bg-brand-navy hover:bg-brand-btn text-white font-semibold rounded-lg transition-colors" data-category="{{ $section['slug'] }}">
                    Load More
                </button>
            </div>
            @endif
        </section>
    @empty
        <p class="text-gray-500 text-center py-12">No articles yet.</p>
    @endforelse
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var excludeIds = @json($featuredIds ?? []);
    var loadMoreRoute = '{{ route("blog.load-more") }}';

    document.querySelectorAll('.blog-load-more').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var section = this.closest('[data-blog-category]');
            if (!section) return;
            var category = section.getAttribute('data-blog-category');
            var offset = parseInt(section.getAttribute('data-blog-offset'), 10) || 12;
            var grid = section.querySelector('.blog-category-grid');
            if (!grid) return;

            var self = this;
            self.disabled = true;
            self.textContent = 'Loading...';

            var url = loadMoreRoute + '?category=' + encodeURIComponent(category) + '&offset=' + offset;
            if (excludeIds && excludeIds.length) url += '&exclude=' + excludeIds.join(',');

            fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function(r) {
                    if (!r.ok) throw new Error('Load failed');
                    return r.json();
                })
                .then(function(data) {
                    if (data.html) {
                        grid.insertAdjacentHTML('beforeend', data.html);
                    }
                    section.setAttribute('data-blog-offset', offset + 12);
                    if (data.hasMore) {
                        self.disabled = false;
                        self.textContent = 'Load More';
                    } else {
                        var wrap = self.closest('.mt-6');
                        if (wrap) wrap.remove();
                    }
                })
                .catch(function() {
                    self.disabled = false;
                    self.textContent = 'Load More';
                });
        });
    });
});
</script>
@endpush
@endsection
