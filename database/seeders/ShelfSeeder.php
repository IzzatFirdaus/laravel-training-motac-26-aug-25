<?php

namespace Database\Seeders;

use App\Models\Shelf;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class ShelfSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = Warehouse::all();

        foreach ($warehouses as $warehouse) {
            for ($i = 1; $i <= 6; $i++) {
                Shelf::firstOrCreate([
                    'warehouse_id' => $warehouse->id,
                    'shelf_number' => sprintf('%s-%02d', strtoupper(substr($warehouse->name, 0, 1)), $i),
                ]);
            }
        }
    }
}
