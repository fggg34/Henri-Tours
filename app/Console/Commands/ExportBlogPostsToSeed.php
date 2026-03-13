<?php

namespace App\Console\Commands;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Console\Command;

class ExportBlogPostsToSeed extends Command
{
    protected $signature = 'blog:export-seed
                            {--output= : Path to output JSON file (default: database/seeders/data/blog_posts.json)}';

    protected $description = 'Export blog posts, categories, and tags to a JSON file for seeding on another server.';

    public function handle(): int
    {
        $path = $this->option('output') ?? database_path('seeders/data/blog_posts.json');
        $dir = dirname($path);
        if (! is_dir($dir)) {
            if (! mkdir($dir, 0755, true)) {
                $this->error("Could not create directory: {$dir}");
                return self::FAILURE;
            }
        }

        $categories = BlogCategory::all()->map(fn ($c) => [
            'name' => $c->name,
            'slug' => $c->slug,
            'description' => $c->description,
        ])->values()->toArray();

        $tags = BlogTag::all()->map(fn ($t) => [
            'name' => $t->name,
            'slug' => $t->slug,
        ])->values()->toArray();

        $posts = BlogPost::with(['category', 'tags'])->orderBy('id')->get()->map(function ($post) {
            return [
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'content' => $post->content,
                'featured_image' => $post->featured_image,
                'meta_title' => $post->meta_title,
                'meta_description' => $post->meta_description,
                'is_published' => $post->is_published,
                'is_featured' => $post->is_featured ?? false,
                'published_at' => $post->published_at?->format('Y-m-d H:i:s'),
                'category_slug' => $post->category?->slug,
                'tag_slugs' => $post->tags->pluck('slug')->values()->toArray(),
            ];
        })->values()->toArray();

        $data = [
            'exported_at' => now()->toIso8601String(),
            'categories' => $categories,
            'tags' => $tags,
            'posts' => $posts,
        ];

        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        if ($json === false) {
            $this->error('Failed to encode JSON.');
            return self::FAILURE;
        }

        if (file_put_contents($path, $json) === false) {
            $this->error("Could not write file: {$path}");
            return self::FAILURE;
        }

        $this->info("Exported to: {$path}");
        $this->info("  Categories: " . count($categories));
        $this->info("  Tags: " . count($tags));
        $this->info("  Posts: " . count($posts));

        return self::SUCCESS;
    }
}
