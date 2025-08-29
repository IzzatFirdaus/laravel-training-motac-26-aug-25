<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        Warehouse::firstOrCreate(['name' => 'Branch A']);
        Warehouse::firstOrCreate(['name' => 'Branch B']);
        Warehouse::firstOrCreate(['name' => 'Branch C']);
    }
}
