<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->decimal('map_lat', 10, 8)->nullable()->after('location');
            $table->decimal('map_lng', 11, 8)->nullable()->after('map_lat');
            $table->json('facilities')->nullable()->after('map_lng'); // [ { "icon": "...", "text": "..." }, ... ]
            $table->json('house_rules')->nullable()->after('facilities'); // [ { "label": "Check In", "value": "12:00 pm" }, ... ]
        });
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn(['map_lat', 'map_lng', 'facilities', 'house_rules']);
        });
    }
};
