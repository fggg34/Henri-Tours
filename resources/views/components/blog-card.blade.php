@props(['post'])

@php
    $imageUrl = $post->featured_image_url ?? 'https://placehold.co/600x400/e2e8f0/64748b?text=Blog';
@endphp
<article class="bg-white rounded-lg overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow group">
    <a href="{{ route('blog.show', $post->slug) }}" class="block">
        <div class="aspect-[16/10] overflow-hidden bg-gray-100">
            <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
        </div>
        <div class="p-5">
            @if($post->published_at)
                <p class="text-xs text-brand-btn font-medium mb-2">{{ $post->published_at->format('d M Y') }}</p>
            @endif
            <h3 class="text-base font-bold text-gray-900 line-clamp-2 leading-snug group-hover:text-brand-navy transition-colors">{{ $post->title }}</h3>
            <p class="mt-2 text-sm text-gray-500 line-clamp-3 leading-relaxed">{{ Str::limit(strip_tags($post->excerpt ?? $post->content ?? ''), 120) }}</p>
            <span class="inline-block mt-3 text-sm font-medium text-brand-btn hover:text-brand-btn-hover transition-colors">Read article &rarr;</span>
        </div>
    </a>
</article>
