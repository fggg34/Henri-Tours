@extends('layouts.site')

@section('title', $title . ' - Blog - ' . config('app.name'))
@section('description', $subtitle)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <header class="mb-10">
        <nav class="text-sm text-gray-500 mb-3">
            <a href="{{ route('blog.index') }}" class="hover:text-brand-navy transition-colors">Blog</a>
            <span class="mx-1">/</span>
            <span class="text-gray-900 font-medium">{{ $title }}</span>
        </nav>
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">{{ $title }}</h1>
        <p class="text-gray-500 text-lg">{{ $subtitle }}</p>
    </header>

    @if($posts->isNotEmpty())
    <section data-blog-archive-type="{{ $archiveType }}" data-blog-archive-slug="{{ $archiveSlug }}" data-blog-offset="12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 blog-archive-grid">
            @foreach($posts as $post)
                <x-blog-card :post="$post" />
            @endforeach
        </div>
        @if($hasMore ?? false)
        <div class="mt-10 text-center">
            <button type="button" class="blog-archive-load-more px-6 py-3 bg-brand-navy hover:bg-brand-btn text-white font-semibold rounded-lg transition-colors">
                Load More
            </button>
        </div>
        @endif
    </section>
    @else
    <p class="text-gray-500 text-center py-16">No posts found.</p>
    @endif
</div>

@push('scripts')
<script>
(function() {
    document.querySelectorAll('.blog-archive-load-more').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var section = this.closest('[data-blog-archive-type]');
            var type = section.getAttribute('data-blog-archive-type');
            var slug = section.getAttribute('data-blog-archive-slug');
            var offset = parseInt(section.getAttribute('data-blog-offset'), 10) || 12;
            var grid = section.querySelector('.blog-archive-grid');

            this.disabled = true;
            this.textContent = 'Loading...';

            var url = '{{ route("blog.load-more") }}?offset=' + offset;
            if (type === 'category') url += '&category=' + encodeURIComponent(slug);
            else if (type === 'tag') url += '&tag=' + encodeURIComponent(slug);

            fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.html) {
                    grid.insertAdjacentHTML('beforeend', data.html);
                }
                section.setAttribute('data-blog-offset', offset + 12);
                if (data.hasMore) {
                    this.disabled = false;
                    this.textContent = 'Load More';
                } else {
                    this.closest('.mt-10').remove();
                }
            }.bind(this))
            .catch(function() {
                this.disabled = false;
                this.textContent = 'Load More';
            }.bind(this));
        });
    });
})();
</script>
@endpush
@endsection
