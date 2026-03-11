@extends('layouts.site')

@section('title', 'Blog - ' . config('app.name'))
@section('description', 'Travel tips, destination guides and news.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Blog</h1>

    @forelse($postsByCategory as $section)
        <section class="mb-12" data-blog-category="{{ $section['slug'] }}" data-blog-offset="12">
            <h2 class="text-xl font-bold text-gray-900 mb-5 pb-2 border-b border-gray-200">{{ $section['name'] }}</h2>
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
document.querySelectorAll('.blog-load-more').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var section = this.closest('[data-blog-category]');
        var category = section.getAttribute('data-blog-category');
        var offset = parseInt(section.getAttribute('data-blog-offset'), 10) || 12;
        var grid = section.querySelector('.blog-category-grid');

        this.disabled = true;
        this.textContent = 'Loading...';

        fetch('{{ route("blog.load-more") }}?category=' + encodeURIComponent(category) + '&offset=' + offset)
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
                    this.closest('.mt-6').remove();
                }
            }.bind(this))
            .catch(function() {
                this.disabled = false;
                this.textContent = 'Load More';
            }.bind(this));
    });
});
</script>
@endpush
@endsection
