@extends('layouts.site')

@section('title', 'Blog - ' . config('app.name'))
@section('description', 'Travel tips, destination guides and news.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Blog</h1>

    @if($categories->isNotEmpty())
        <div class="flex flex-wrap gap-2 mb-8">
            <a href="{{ route('blog.index') }}" class="px-4 py-2 rounded-full text-sm font-medium {{ !request('category') ? 'bg-brand-btn text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">All</a>
            @foreach($categories as $c)
                <a href="{{ route('blog.index', ['category' => $c->slug]) }}" class="px-4 py-2 rounded-full text-sm font-medium {{ request('category') === $c->slug ? 'bg-brand-btn text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">{{ $c->name }}</a>
            @endforeach
        </div>
    @endif

    @forelse($postsByCategory as $categoryName => $categoryPosts)
        <section class="mb-12">
            <h2 class="text-xl font-bold text-gray-900 mb-5 pb-2 border-b border-gray-200">{{ $categoryName }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($categoryPosts as $post)
                    <x-blog-card :post="$post" />
                @endforeach
            </div>
        </section>
    @empty
        <p class="text-gray-500 text-center py-12">No articles yet.</p>
    @endforelse
</div>
@endsection
