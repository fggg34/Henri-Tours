<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('guest_email');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('pickup_location')->nullable()->after('guest_phone');
            $table->string('billing_country')->nullable()->after('special_requests');
            $table->string('billing_region')->nullable()->after('billing_country');
            $table->string('billing_city')->nullable()->after('billing_region');
            $table->string('billing_address')->nullable()->after('billing_city');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'pickup_location',
                'billing_country', 'billing_region', 'billing_city', 'billing_address',
            ]);
        });
    }
};
