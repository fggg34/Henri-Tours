<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('highlight_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('highlight_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();

            $table->unique(['highlight_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('highlight_translations');
    }
};
