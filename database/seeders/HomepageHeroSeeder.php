<?php

namespace Database\Seeders;

use App\Models\HomepageHero;
use Illuminate\Database\Seeder;

class HomepageHeroSeeder extends Seeder
{
    public function run(): void
    {
        HomepageHero::updateOrCreate(
            ['id' => 1],
            [
                'title' => 'Adventure Simplified',
                'subtitle' => 'Guides, local transport, accommodation, and like-minded travelers are always included. Book securely & flexibly.',
                'banner_type' => 'image',
                'banner_image' => null,
                'banner_video' => null,
                'cta_text' => null,
                'is_active' => true,
            ]
        );
    }
}
