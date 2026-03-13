<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_activity_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_activity_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('title');
            $table->timestamps();

            $table->unique(['tour_activity_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_activity_translations');
    }
};
