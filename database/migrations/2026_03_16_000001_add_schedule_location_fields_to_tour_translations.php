<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tour_translations', function (Blueprint $table) {
            $table->string('start_location')->nullable()->after('short_description');
            $table->string('end_location')->nullable()->after('start_location');
            $table->string('start_time', 50)->nullable()->after('end_location');
            $table->json('languages')->nullable()->after('start_time');
        });
    }

    public function down(): void
    {
        Schema::table('tour_translations', function (Blueprint $table) {
            $table->dropColumn(['start_location', 'end_location', 'start_time', 'languages']);
        });
    }
};
