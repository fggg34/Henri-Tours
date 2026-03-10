<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Migrate existing city_id to pivot
        $tours = DB::table('tours')->whereNotNull('city_id')->pluck('city_id', 'id');
        foreach ($tours as $tourId => $cityId) {
            DB::table('city_tour')->insertOrIgnore([
                'city_id' => $cityId,
                'tour_id' => $tourId,
            ]);
        }

        Schema::table('tours', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropColumn('city_id');
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->after('category_id')->constrained('cities')->nullOnDelete();
        });

        // Restore first city from pivot
        $pivots = DB::table('city_tour')->orderBy('city_id')->get()->groupBy('tour_id');
        foreach ($pivots as $tourId => $rows) {
            $firstCityId = $rows->first()->city_id;
            DB::table('tours')->where('id', $tourId)->update(['city_id' => $firstCityId]);
        }
    }
};
