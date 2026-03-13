<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->string('short_description')->nullable();
            $table->json('included')->nullable();
            $table->json('not_included')->nullable();
            $table->json('what_to_bring')->nullable();
            $table->text('important_notes')->nullable();
            $table->json('tour_highlights')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->timestamps();

            $table->unique(['tour_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_translations');
    }
};
