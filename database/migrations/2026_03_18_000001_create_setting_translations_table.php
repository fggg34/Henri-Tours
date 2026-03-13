<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setting_translations', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key')->index();
            $table->string('locale', 10)->index();
            $table->longText('value')->nullable();
            $table->timestamps();

            $table->unique(['setting_key', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setting_translations');
    }
};
