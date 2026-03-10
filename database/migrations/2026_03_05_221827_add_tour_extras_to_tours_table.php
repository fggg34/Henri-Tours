<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->json('what_to_bring')->nullable()->after('not_included');
            $table->text('important_notes')->nullable()->after('what_to_bring');
            $table->string('season', 20)->nullable()->after('important_notes')->comment('summer, winter, all_season');
            $table->json('tour_highlights')->nullable()->after('season');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn(['what_to_bring', 'important_notes', 'season', 'tour_highlights']);
        });
    }
};
