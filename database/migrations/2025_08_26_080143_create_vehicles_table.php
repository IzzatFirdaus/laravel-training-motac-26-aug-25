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
        // Create the vehicles table. This migration was adapted to also
        // include basic inventory-like fields (name, qty, price, description)
        // to reuse the same UI patterns as the inventories table.
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            // Link to user who owns/created the vehicle; nullable for parity
            // with inventories and to avoid blocking unauthenticated inserts.
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Inventory-like fields (added directly here to consolidate schema)
            // keep fields nullable/defaults to avoid migration conflicts
            $table->string('name')->nullable();
            $table->integer('qty')->default(0);
            $table->decimal('price', 10, 2)->nullable();
            $table->text('description')->nullable();

            // Vehicle-specific fields (kept commented out)
            // $table->string('vin')->nullable()->unique();
            // $table->unsignedInteger('mileage')->nullable();
            // $table->string('status')->default('available');

            $table->timestamps();
            // Soft deletes column for the Vehicle model
            $table->softDeletes();
        });

        // Pivot table for many-to-many between inventories and vehicles
        Schema::create('inventory_vehicle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventories')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
