<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_hero_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('homepage_hero_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->timestamps();

            $table->unique(['homepage_hero_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_hero_translations');
    }
};
