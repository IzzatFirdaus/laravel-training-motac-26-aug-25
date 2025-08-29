<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        Warehouse::firstOrCreate(['name' => 'Branch A']);
        Warehouse::firstOrCreate(['name' => 'Branch B']);
        Warehouse::firstOrCreate(['name' => 'Branch C']);
    }
}
