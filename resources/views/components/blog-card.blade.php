@props(['post'])

@php
    $imageUrl = $post->featured_image_url ?? 'https://placehold.co/600x400/e2e8f0/64748b?text=Blog';
    $postTitle = $post->translate('title');
    $postExcerpt = $post->translate('excerpt') ?? $post->translate('content');
@endphp
<article class="group bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-3">
    <a href="{{ localized_route('blog.show', ['slug' => $post->slug]) }}" class="block">
        <div class="relative aspect-[4/3] overflow-hidden rounded-xl bg-gray-100 mb-4">
            <img src="{{ $imageUrl }}" alt="{{ $postTitle }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
            @if($post->published_at)
                <span class="absolute bottom-3 left-3 bg-gray-900/70 text-white text-[11px] font-medium px-2.5 py-1 rounded">{{ $post->published_at->format('d M Y') }}</span>
            @endif
        </div>
        <h3 class="text-[15px] font-bold text-gray-900 line-clamp-2 leading-snug mb-2 group-hover:text-brand-navy transition-colors">{{ $postTitle }}</h3>
        <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed mb-3">{{ Str::limit(strip_tags($postExcerpt ?? ''), 120) }}</p>
        <span class="inline-block text-sm font-semibold text-brand-btn hover:text-brand-btn-hover transition-colors">Read article &rarr;</span>
    </a>
</article>
