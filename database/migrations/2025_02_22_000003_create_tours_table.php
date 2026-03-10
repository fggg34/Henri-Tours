<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('tour_categories')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->string('short_description')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->string('currency', 3)->default('EUR');
            $table->unsignedInteger('duration_hours')->nullable();
            $table->unsignedInteger('duration_days')->nullable();
            $table->string('start_time')->nullable();
            $table->string('start_location')->nullable();
            $table->string('end_location')->nullable();
            $table->unsignedInteger('max_group_size')->nullable();
            $table->json('languages')->nullable();
            $table->json('included')->nullable();
            $table->json('not_included')->nullable();
            $table->decimal('map_lat', 10, 8)->nullable();
            $table->decimal('map_lng', 11, 8)->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
