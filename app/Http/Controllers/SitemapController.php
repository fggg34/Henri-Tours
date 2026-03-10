<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Tour;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [];
        $base = config('app.url');

        $urls[] = ['loc' => $base, 'changefreq' => 'daily', 'priority' => '1.0'];
        $urls[] = ['loc' => $base . '/tours', 'changefreq' => 'daily', 'priority' => '0.9'];
        $urls[] = ['loc' => $base . '/blog', 'changefreq' => 'daily', 'priority' => '0.8'];
        $urls[] = ['loc' => $base . '/about', 'changefreq' => 'monthly', 'priority' => '0.5'];
        $urls[] = ['loc' => $base . '/contact', 'changefreq' => 'monthly', 'priority' => '0.5'];

        Tour::where('is_active', true)->get(['slug', 'updated_at'])->each(function ($tour) use (&$urls, $base) {
            $urls[] = ['loc' => $base . '/tours/' . $tour->slug, 'changefreq' => 'weekly', 'priority' => '0.8', 'lastmod' => $tour->updated_at->toW3cString()];
        });

        BlogPost::where('is_published', true)->get(['slug', 'updated_at'])->each(function ($post) use (&$urls, $base) {
            $urls[] = ['loc' => $base . '/blog/' . $post->slug, 'changefreq' => 'monthly', 'priority' => '0.6', 'lastmod' => $post->updated_at->toW3cString()];
        });

        $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($urls as $u) {
            $xml .= '<url><loc>' . htmlspecialchars($u['loc']) . '</loc><changefreq>' . ($u['changefreq'] ?? 'weekly') . '</changefreq><priority>' . ($u['priority'] ?? '0.5') . '</priority>';
            if (!empty($u['lastmod'])) {
                $xml .= '<lastmod>' . $u['lastmod'] . '</lastmod>';
            }
            $xml .= '</url>';
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
