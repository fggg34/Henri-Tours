<?php

use App\Models\City;
use App\Models\Hotel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        foreach (City::whereNull('slug')->orWhere('slug', '')->get() as $city) {
            $city->slug = Str::slug($city->name);
            $base = $city->slug;
            $i = 1;
            while (City::where('slug', $city->slug)->where('id', '!=', $city->id)->exists()) {
                $city->slug = $base . '-' . $i++;
            }
            $city->saveQuietly();
        }

        foreach (Hotel::whereNull('slug')->orWhere('slug', '')->get() as $hotel) {
            $hotel->slug = Str::slug($hotel->name);
            $base = $hotel->slug;
            $i = 1;
            while (Hotel::where('slug', $hotel->slug)->where('id', '!=', $hotel->id)->exists()) {
                $hotel->slug = $base . '-' . $i++;
            }
            $hotel->saveQuietly();
        }
    }

    public function down(): void
    {
        // No-op: slugs remain
    }
};
