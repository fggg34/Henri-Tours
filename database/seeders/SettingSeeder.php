<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'Albania Inbound',
            'site_tagline' => 'Discover Your Next Adventure',
            'contact_email' => 'info@albaniainbound.com',
            'contact_phone' => '+355 00 000 0000',
            'contact_address' => '123 Main Street, City, Country',
            'currency' => 'EUR',
            'currency_symbol' => '€',
            'hero_title' => 'Book Your Perfect Tour',
            'hero_subtitle' => 'Explore stunning destinations with expert guides.',
            'facebook_url' => '',
            'instagram_url' => '',
            'twitter_url' => '',
            'map_embed' => '',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
