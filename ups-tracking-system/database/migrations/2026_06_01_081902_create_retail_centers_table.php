<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the retail_centers table for UPS retail intake locations.
     */
    public function up(): void
    {
        Schema::create('retail_centers', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id', 100)->unique()->comment('Unique identifier for the retail center');
            $table->string('type', 50)->comment('Type of center (e.g., Hub, Drop-off, Service Center)');
            $table->text('address')->comment('Full physical address of the retail center');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retail_centers');
    }
};
