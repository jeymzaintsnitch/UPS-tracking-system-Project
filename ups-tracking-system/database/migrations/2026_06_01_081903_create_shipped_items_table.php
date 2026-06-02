<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the shipped_items table — the core entity of the UPS tracking system.
     */
    public function up(): void
    {
        Schema::create('shipped_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_number', 100)->unique()->comment('Unique tracking / item number');
            $table->decimal('weight', 10, 2)->comment('Weight in kilograms');
            $table->string('dimensions', 100)->comment('Dimensions (e.g., 30x20x15 cm)');
            $table->decimal('insurance_amount', 12, 2)->default(0)->comment('Insurance value in PHP');
            $table->string('destination')->comment('Delivery destination address');
            $table->date('final_delivery_date')->nullable()->comment('Actual or estimated delivery date');
            $table->foreignId('retail_center_id')
                  ->constrained('retail_centers')
                  ->onDelete('cascade')
                  ->comment('FK to the retail center where the item was received');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipped_items');
    }
};
