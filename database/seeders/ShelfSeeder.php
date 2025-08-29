<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use App\Models\Shelf;

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
