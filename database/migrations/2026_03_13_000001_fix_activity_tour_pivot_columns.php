<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('activity_tour');

        Schema::create('activity_tour', function (Blueprint $table) {
            $table->foreignId('tour_activity_id')->constrained('tour_activities')->cascadeOnDelete();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->primary(['tour_activity_id', 'tour_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_tour');

        Schema::create('activity_tour', function (Blueprint $table) {
            $table->foreignId('activity_id')->constrained('tour_activities')->cascadeOnDelete();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->primary(['activity_id', 'tour_id']);
        });
    }
};
