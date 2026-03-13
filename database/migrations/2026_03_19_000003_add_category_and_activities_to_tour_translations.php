<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tour_translations', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('tour_id')->constrained('tour_categories')->nullOnDelete();
        });

        Schema::create('activity_tour_translation', function (Blueprint $table) {
            $table->foreignId('tour_translation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tour_activity_id')->constrained()->cascadeOnDelete();
            $table->primary(['tour_translation_id', 'tour_activity_id']);
        });
    }

    public function down(): void
    {
        Schema::table('tour_translations', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });
        Schema::dropIfExists('activity_tour_translation');
    }
};
