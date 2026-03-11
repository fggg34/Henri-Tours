@props(['post', 'variant' => 'small'])

@php
    $imageUrl = $post->featured_image_url ?? 'https://placehold.co/600x400/e2e8f0/64748b?text=Blog';
    $excerpt = Str::limit(strip_tags($post->excerpt ?? $post->content ?? ''), $variant === 'large' ? 280 : 100);
@endphp
<article class="group bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden flex flex-col h-full">
    <a href="{{ route('blog.show', $post->slug) }}" class="block flex flex-col h-full">
        <div class="relative overflow-hidden bg-gray-100 {{ $variant === 'large' ? 'aspect-[3/4]' : 'aspect-[4/3]' }}">
            <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
            <span class="absolute top-3 left-3 bg-gray-700 text-white text-[11px] font-medium px-2.5 py-1 rounded">Featured</span>
            @if($post->published_at)
            <span class="absolute bottom-3 left-3 bg-white/95 text-gray-600 text-[11px] font-medium px-2.5 py-1 rounded shadow-sm">{{ $post->published_at->format('d M Y') }}</span>
            @endif
        </div>
        <div class="p-4 flex-1 flex flex-col">
            <h3 class="text-base font-bold text-gray-900 line-clamp-2 leading-snug mb-2 group-hover:text-brand-navy transition-colors">{{ $post->title }}</h3>
            <p class="text-sm text-gray-500 {{ $variant === 'large' ? 'line-clamp-3' : 'line-clamp-2' }} leading-relaxed mb-3 flex-1">{{ $excerpt }}</p>
            <span class="inline-block text-sm font-semibold text-brand-btn hover:text-brand-btn-hover transition-colors">Read article &rarr;</span>
        </div>
    </a>
</article>
