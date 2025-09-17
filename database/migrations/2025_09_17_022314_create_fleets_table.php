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
        // database/migrations/xxxx_create_fleets_table.php
        Schema::create('fleets', function (Blueprint $table) {
            $table->id();
            $table->string('fleet_number')->unique();
            $table->enum('vehicle_type', ['truck', 'van', 'motorcycle', 'car']);
            $table->enum('availability', ['available', 'unavailable'])->default('available');
            $table->decimal('capacity', 8, 2); // dalam ton
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fleets');
    }
};
