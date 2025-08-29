<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('inventories')) {
            return;
        }

        Schema::table('inventories', function (Blueprint $table): void {
            if (! Schema::hasColumn('inventories', 'warehouse_id')) {
                $table->foreignId('warehouse_id')->nullable()->after('user_id')->constrained('warehouses')->nullOnDelete();
            }
            if (! Schema::hasColumn('inventories', 'shelf_id')) {
                $table->foreignId('shelf_id')->nullable()->after('warehouse_id')->constrained('shelves')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('inventories')) {
            return;
        }

        Schema::table('inventories', function (Blueprint $table): void {
            if (Schema::hasColumn('inventories', 'shelf_id')) {
                $table->dropConstrainedForeignId('shelf_id');
            }
            if (Schema::hasColumn('inventories', 'warehouse_id')) {
                $table->dropConstrainedForeignId('warehouse_id');
            }
        });
    }
};
