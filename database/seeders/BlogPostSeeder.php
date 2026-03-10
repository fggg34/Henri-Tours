<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $category = BlogCategory::where('slug', 'travel-tips')->first();
        if (! $user || ! $category) {
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
            $slug = \Illuminate\Support\Str::slug($data['title']);
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
