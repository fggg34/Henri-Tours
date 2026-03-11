<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transport_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('telephone', 50);
            $table->date('travel_date');
            $table->date('travel_end_date');
            $table->string('pickup_location');
            $table->string('dropoff_location');
            $table->string('preferred_vehicle')->nullable();
            $table->unsignedInteger('group_size')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_bookings');
    }
};
