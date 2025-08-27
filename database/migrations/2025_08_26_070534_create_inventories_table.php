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
    // Create inventories table with attributes used by the app views.
    // Columns: user_id (owner), name, qty, price, description, timestamps.
    Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            // owner of the inventory item; nullable so unauthenticated users
            // can create items in development/testing. on delete -> set null
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name')->nullable();
            $table->integer('qty')->default(0);
            // price stored with precision and scale
            $table->decimal('price', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    // Note: pivot table `inventory_vehicle` is created in the vehicles migration
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
