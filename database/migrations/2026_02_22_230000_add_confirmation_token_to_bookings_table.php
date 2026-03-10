<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('confirmation_token', 64)->nullable()->unique()->after('id');
        });

        // Backfill tokens for existing bookings so old links can be migrated
        $bookings = DB::table('bookings')->whereNull('confirmation_token')->get();
        foreach ($bookings as $booking) {
            DB::table('bookings')->where('id', $booking->id)->update([
                'confirmation_token' => Str::random(64),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('confirmation_token');
        });
    }
};
