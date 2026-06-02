<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the pivot table linking shipped_items ↔ transportation_events (M:N).
     */
    public function up(): void
    {
        Schema::create('shipped_item_transportation_event', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipped_item_id');
            $table->unsignedBigInteger('transportation_event_id');
            $table->timestamps();

            // Foreign keys with short custom names to avoid MySQL identifier length limit
            $table->foreign('shipped_item_id', 'si_te_si_fk')
                  ->references('id')->on('shipped_items')
                  ->onDelete('cascade');

            $table->foreign('transportation_event_id', 'si_te_te_fk')
                  ->references('id')->on('transportation_events')
                  ->onDelete('cascade');

            $table->unique(['shipped_item_id', 'transportation_event_id'], 'si_te_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipped_item_transportation_event');
    }
};
