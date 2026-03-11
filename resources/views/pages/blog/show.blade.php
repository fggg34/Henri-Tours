@extends('layouts.site')

@section('title', $post->meta_title ?: $post->title . ' - ' . config('app.name'))
@section('description', $post->meta_description ?: Str::limit(strip_tags($post->excerpt ?? ''), 160))

@section('content')
<article class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <p class="text-sm text-gray-500">{{ $post->published_at?->format('F j, Y') }}</p>
    <h1 class="text-4xl font-bold text-gray-900 mt-2">{{ $post->title }}</h1>
    @if($post->featured_image_url)
        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="mt-6 w-full rounded-xl">
    @endif
    <div class="blog-content mt-6">
        {!! $post->content !!}
    </div>

    @if($post->category || $post->tags->isNotEmpty())
    <div class="mt-10 pt-8 border-t border-gray-200">
        <div class="flex flex-wrap flex-col items-left gap-4 text-sm">
            @if($post->category)
                <div class="flex items-center gap-2">
                    <span class="text-gray-400 font-medium uppercase tracking-wider">Category</span>
                    <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-brand-navy font-medium hover:bg-blue-100 transition-colors">{{ $post->category->name }}</a>
                </div>
            @endif
            @if($post->tags->isNotEmpty())
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-gray-400 font-medium uppercase tracking-wider">Tags</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                            <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 hover:text-gray-900 transition-colors">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif
</article>

@if($related->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-t border-gray-200">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Related articles</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($related as $p)
            <x-blog-card :post="$p" />
        @endforeach
    </div>
</section>
@endif
@endsection
