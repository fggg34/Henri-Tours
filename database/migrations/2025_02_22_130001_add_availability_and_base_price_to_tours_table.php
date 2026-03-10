<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->decimal('base_price', 12, 2)->nullable()->after('price');
            $table->date('availability_start_date')->nullable()->after('sort_order');
            $table->date('availability_end_date')->nullable()->after('availability_start_date');
            $table->json('closed_dates')->nullable()->after('availability_end_date');
            $table->json('available_weekdays')->nullable()->after('closed_dates');
            $table->unsignedInteger('default_daily_capacity')->nullable()->after('available_weekdays');
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn([
                'base_price',
                'availability_start_date',
                'availability_end_date',
                'closed_dates',
                'available_weekdays',
                'default_daily_capacity',
            ]);
        });
    }
};
