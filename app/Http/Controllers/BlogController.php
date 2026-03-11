<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $allPosts = BlogPost::where('is_published', true)
            ->whereNotNull('published_at')
            ->with('category')
            ->orderByDesc('published_at')
            ->get();

        $grouped = $allPosts->groupBy(function ($post) {
            return $post->category ? $post->category->name : 'Uncategorized';
        });

        $postsByCategory = [];
        foreach ($grouped as $categoryName => $catPosts) {
            $posts = $catPosts->values();
            $total = $posts->count();
            $initial = $posts->take(12)->values();
            $first = $catPosts->first();
            $slug = $first->category ? $first->category->slug : 'uncategorized';

            $postsByCategory[] = [
                'name' => $categoryName,
                'slug' => $slug,
                'posts' => $initial,
                'total' => $total,
                'hasMore' => $total > 12,
            ];
        }

        return view('pages.blog.index', compact('postsByCategory'));
    }

    public function loadMore(Request $request)
    {
        $category = $request->get('category');
        $offset = (int) $request->get('offset', 12);

        $query = BlogPost::where('is_published', true)
            ->whereNotNull('published_at')
            ->with('category')
            ->orderByDesc('published_at');

        if ($category === 'uncategorized') {
            $query->whereNull('blog_category_id');
        } else {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        $total = $query->count();
        $posts = $query->skip($offset)->take(12)->get();
        $hasMore = $offset + 12 < $total;

        $html = view('pages.blog.partials.cards', compact('posts'))->render();

        return response()->json(['html' => $html, 'hasMore' => $hasMore]);
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
            ->limit(3)
            ->get();

        return view('pages.blog.show', compact('post', 'related'));
    }
}
