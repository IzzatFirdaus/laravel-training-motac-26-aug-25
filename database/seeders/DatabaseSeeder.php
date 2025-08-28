<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure a Test User exists without violating unique constraints on repeated seeds.
        User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
        ]);
        // Delegate seeding to specialized seeders (keeps this file tidy)
        $this->call([
            UserSeeder::class,
            VehicleSeeder::class,
            InventorySeeder::class,
        ]);
    }
}
