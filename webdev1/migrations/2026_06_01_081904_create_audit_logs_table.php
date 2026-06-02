<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the audit_logs table for tracking all user actions in the system.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('FK to the user who performed the action');
            $table->string('action', 50)->comment('Action type: CREATE, UPDATE, DELETE');
            $table->string('entity', 100)->comment('Entity/model name affected');
            $table->unsignedBigInteger('entity_id')->comment('Primary key of the affected entity');
            $table->json('old_values')->nullable()->comment('Previous values before the change');
            $table->json('new_values')->nullable()->comment('New values after the change');
            $table->timestamps();

            $table->index(['entity', 'entity_id']);
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
