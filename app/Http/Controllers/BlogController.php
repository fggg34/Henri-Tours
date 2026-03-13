<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $baseQuery = BlogPost::where('is_published', true)
            ->whereNotNull('published_at')
            ->with('category')
            ->orderByDesc('published_at');

        // Get 5 featured posts: featured first, fill with random if < 5
        $featuredIds = $baseQuery->clone()->where('is_featured', true)->limit(5)->pluck('id');
        $needed = 5 - $featuredIds->count();
        if ($needed > 0) {
            $randomIds = $baseQuery->clone()
                ->whereNotIn('id', $featuredIds)
                ->inRandomOrder()
                ->limit($needed)
                ->pluck('id');
            $featuredIds = $featuredIds->merge($randomIds);
        }
        $featuredPosts = $baseQuery->clone()->whereIn('id', $featuredIds)->get();
        // Order: first the main (left), then the 4 small (right) - maintain published_at order within our 5
        $featuredPosts = $featuredPosts->sortByDesc('published_at')->values();
        $mainPost = $featuredPosts->first();
        $sidePosts = $featuredPosts->slice(1, 4);

        // Category posts: exclude featured 5
        $allPosts = $baseQuery->clone()->whereNotIn('id', $featuredIds)->get();

        $locale = app()->getLocale();
        $grouped = $allPosts->groupBy(function ($post) use ($locale) {
            return $post->category ? ($post->category->translate('name', $locale) ?? $post->category->name) : 'Uncategorized';
        });

        $postsByCategory = [];
        foreach ($grouped as $categoryName => $catPosts) {
            $posts = $catPosts->values();
            $total = $posts->count();
            $initial = $posts->take(12)->values();
            $first = $catPosts->first();
            $slug = $first->category ? $first->category->getTranslatedSlug($locale) : 'uncategorized';

            $postsByCategory[] = [
                'name' => $categoryName,
                'slug' => $slug,
                'posts' => $initial,
                'total' => $total,
                'hasMore' => $total > 12,
            ];
        }

        return view('pages.blog.index', compact('featuredPosts', 'mainPost', 'sidePosts', 'postsByCategory', 'featuredIds'));
    }

    public function loadMore(Request $request)
    {
        $category = $request->get('category');
        $tag = $request->get('tag');
        $offset = (int) $request->get('offset', 12);
        $excludeIds = $request->get('exclude'); // comma-separated IDs of featured posts

        $query = BlogPost::where('is_published', true)
            ->whereNotNull('published_at')
            ->with('category')
            ->orderByDesc('published_at');

        if ($excludeIds) {
            $ids = array_filter(array_map('intval', explode(',', $excludeIds)));
            if (! empty($ids)) {
                $query->whereNotIn('id', $ids);
            }
        }

        if ($tag) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $tag)->orWhereHas('translations', fn ($t) => $t->where('slug', $tag)));
        } elseif ($category === 'uncategorized') {
            $query->whereNull('blog_category_id');
        } elseif ($category) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category)->orWhereHas('translations', fn ($t) => $t->where('slug', $category)));
        }

        $total = $query->count();
        $posts = $query->skip($offset)->take(12)->get();
        $hasMore = $offset + 12 < $total;

        $html = view('pages.blog.partials.cards', compact('posts'))->render();

        return response()->json(['html' => $html, 'hasMore' => $hasMore]);
    }

    public function uncategorizedArchive()
    {
        $query = BlogPost::where('is_published', true)
            ->whereNotNull('published_at')
            ->whereNull('blog_category_id')
            ->with('category')
            ->orderByDesc('published_at');

        $total = $query->count();
        $posts = $query->limit(12)->get();
        $hasMore = $total > 12;

        return view('pages.blog.archive', [
            'title' => 'Uncategorized',
            'subtitle' => 'Posts without a category',
            'posts' => $posts,
            'archiveType' => 'category',
            'archiveSlug' => 'uncategorized',
            'hasMore' => $hasMore,
            'excludeIds' => [],
        ]);
    }

    public function categoryArchive(BlogCategory $category)
    {
        $query = BlogPost::where('is_published', true)
            ->whereNotNull('published_at')
            ->where('blog_category_id', $category->id)
            ->with('category')
            ->orderByDesc('published_at');

        $total = $query->count();
        $posts = $query->limit(12)->get();
        $hasMore = $total > 12;

        $locale = app()->getLocale();
        return view('pages.blog.archive', [
            'title' => $category->translate('name', $locale),
            'subtitle' => 'Posts in ' . $category->translate('name', $locale),
            'posts' => $posts,
            'archiveType' => 'category',
            'archiveSlug' => $category->getTranslatedSlug($locale),
            'hasMore' => $hasMore,
            'excludeIds' => [],
        ]);
    }

    public function tagArchive(BlogTag $tag)
    {
        $query = BlogPost::where('is_published', true)
            ->whereNotNull('published_at')
            ->whereHas('tags', fn ($q) => $q->where('blog_tags.id', $tag->id))
            ->with('category')
            ->orderByDesc('published_at');

        $total = $query->count();
        $posts = $query->limit(12)->get();
        $hasMore = $total > 12;

        $locale = app()->getLocale();
        return view('pages.blog.archive', [
            'title' => $tag->translate('name', $locale) ?? $tag->name,
            'subtitle' => 'Posts tagged with ' . ($tag->translate('name', $locale) ?? $tag->name),
            'posts' => $posts,
            'archiveType' => 'tag',
            'archiveSlug' => $tag->getTranslatedSlug($locale),
            'hasMore' => $hasMore,
            'excludeIds' => [],
        ]);
    }

    public function show(string $slug)
    {
        $post = BlogPost::where('slug', $slug)->where('is_published', true)
            ->with(['category', 'tags'])
            ->firstOrFail();

        $related = BlogPost::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where(function ($q) use ($post) {
                $q->where('blog_category_id', $post->blog_category_id)->orWhereNull('blog_category_id');
            })
            ->orderByDesc('published_at')
            ->limit(4)
            ->get();

        return view('pages.blog.show', compact('post', 'related'));
    }
}
