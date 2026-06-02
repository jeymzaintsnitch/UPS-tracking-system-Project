<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the transportation_events table for flights, trucks, etc.
     */
    public function up(): void
    {
        Schema::create('transportation_events', function (Blueprint $table) {
            $table->id();
            $table->string('schedule_number', 100)->unique()->comment('Unique schedule identifier');
            $table->string('type', 50)->comment('Transportation type (e.g., Flight, Truck, Ship)');
            $table->string('delivery_route')->comment('Route description (e.g., Manila → Cebu)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transportation_events');
    }
};
