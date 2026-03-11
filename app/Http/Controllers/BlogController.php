<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::where('is_published', true)->whereNotNull('published_at')->with('category')->orderByDesc('published_at');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        $posts = $query->get();
        $categories = BlogCategory::orderBy('name')->get();

        // Group posts by category (including uncategorized)
        $postsByCategory = $posts->groupBy(function ($post) {
            return $post->category ? $post->category->name : 'Uncategorized';
        });

        return view('pages.blog.index', compact('posts', 'postsByCategory', 'categories'));
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
