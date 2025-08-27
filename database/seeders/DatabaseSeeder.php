<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Inventory;
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

        // Create demo users with related vehicles and inventories.
        User::factory(10)->create()->each(function (User $user) {
            // Create 1-5 vehicles for the user
            $vehicles = Vehicle::factory(rand(1, 5))->create(['user_id' => $user->getKey()]);

            // Create 1-6 inventory items for the user
            $inventories = Inventory::factory(rand(1, 6))->create(['user_id' => $user->getKey()]);

            // Attach each inventory to 0..3 random vehicles (many-to-many)
            foreach ($inventories as $inventory) {
                $attachCount = $vehicles->count() ? rand(0, min(3, $vehicles->count())) : 0;
                if ($attachCount > 0) {
                    $attachIds = $vehicles->random($attachCount)->pluck('id')->toArray();
                    $inventory->vehicles()->attach($attachIds);
                }
            }

            // Set a primary vehicle for the user
            if ($vehicles->count()) {
                $user->primary_vehicle_id = $vehicles->random()->id;
                $user->save();
            }
        });
    }
}
