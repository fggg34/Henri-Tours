<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (! $user) {
            return;
        }

        $path = database_path('seeders/data/blog_posts.json');
        if (! is_file($path) || ! is_readable($path)) {
            $this->seedSamplePosts($user);
            return;
        }

        $data = json_decode(file_get_contents($path), true);
        if (! is_array($data)) {
            return;
        }

        // Clear existing posts so we get a clean import from the JSON
        \Illuminate\Support\Facades\DB::table('blog_post_tag')->delete();
        BlogPost::query()->delete();

        $categoryMap = [];
        foreach ($data['categories'] ?? [] as $cat) {
            $slug = $cat['slug'] ?? Str::slug($cat['name'] ?? '');
            if (empty($slug)) {
                continue;
            }
            $category = BlogCategory::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $cat['name'] ?? $slug,
                    'description' => $cat['description'] ?? null,
                ]
            );
            $categoryMap[$slug] = $category->id;
        }

        $tagMap = [];
        foreach ($data['tags'] ?? [] as $tag) {
            $slug = $tag['slug'] ?? Str::slug($tag['name'] ?? '');
            if (empty($slug)) {
                continue;
            }
            $blogTag = BlogTag::firstOrCreate(
                ['slug' => $slug],
                ['name' => $tag['name'] ?? $slug]
            );
            $tagMap[$slug] = $blogTag->id;
        }

        foreach ($data['posts'] ?? [] as $postData) {
            $slug = $postData['slug'] ?? Str::slug($postData['title'] ?? '');
            if (empty($slug)) {
                continue;
            }

            $categorySlug = $postData['category_slug'] ?? null;
            $categoryId = $categorySlug && isset($categoryMap[$categorySlug])
                ? $categoryMap[$categorySlug]
                : null;

            $tagSlugs = $postData['tag_slugs'] ?? [];
            $tagIds = [];
            foreach ($tagSlugs as $ts) {
                if (isset($tagMap[$ts])) {
                    $tagIds[] = $tagMap[$ts];
                }
            }

            $publishedAt = $postData['published_at'] ?? null;
            if ($publishedAt) {
                try {
                    $publishedAt = \Carbon\Carbon::parse($publishedAt);
                } catch (\Throwable) {
                    $publishedAt = now();
                }
            } else {
                $publishedAt = now();
            }

            $post = BlogPost::firstOrCreate(
                ['slug' => $slug],
                [
                    'blog_category_id' => $categoryId,
                    'user_id' => $user->id,
                    'title' => $postData['title'] ?? 'Untitled',
                    'excerpt' => $postData['excerpt'] ?? null,
                    'content' => $postData['content'] ?? null,
                    'featured_image' => $postData['featured_image'] ?? null,
                    'meta_title' => $postData['meta_title'] ?? ($postData['title'] ?? null),
                    'meta_description' => $postData['meta_description'] ?? null,
                    'is_published' => (bool) ($postData['is_published'] ?? true),
                    'is_featured' => (bool) ($postData['is_featured'] ?? false),
                    'published_at' => $publishedAt,
                ]
            );
            $post->tags()->sync(array_values(array_unique($tagIds)));
        }
    }

    private function seedSamplePosts(User $user): void
    {
        $category = BlogCategory::where('slug', 'travel-tips')->first();
        if (! $category) {
            return;
        }

        $posts = [
            [
                'title' => '10 Essential Tips for Your First Tour',
                'excerpt' => 'Make the most of your first guided tour with these expert tips.',
                'content' => '<p>Planning your first tour? Here are ten tips to ensure a smooth and enjoyable experience...</p>',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Best Seasons for Coastal Tours',
                'excerpt' => 'Discover the ideal times to explore the coast.',
                'content' => '<p>Weather and season play a big role in coastal experiences. We break down the best months...</p>',
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
        ];

        foreach ($posts as $data) {
            $slug = Str::slug($data['title']);
            BlogPost::firstOrCreate(
                ['slug' => $slug],
                array_merge($data, [
                    'blog_category_id' => $category->id,
                    'user_id' => $user->id,
                    'meta_title' => $data['title'],
                    'meta_description' => $data['excerpt'],
                ])
            );
        }
    }
}
