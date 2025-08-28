<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo vehicles; some vehicles will be owned by users
        \App\Models\Vehicle::factory(30)->create();
    }
}
