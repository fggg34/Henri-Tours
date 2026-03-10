<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('tours')->whereNull('base_price')->update(['base_price' => DB::raw('price')]);
    }

    public function down(): void
    {
        // no-op
    }
};
