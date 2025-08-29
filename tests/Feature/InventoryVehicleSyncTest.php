<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryVehicleSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_attaches_selected_vehicles(): void
    {
        $admin = User::factory()->admin()->create();

        // Create two vehicles to attach
        $v1 = Vehicle::factory()->create();
        $v2 = Vehicle::factory()->create();

        $response = $this->actingAs($admin)
            ->post('/inventories', [
                'name' => 'Inventory with vehicles',
                'qty' => 5,
                'price' => 123.45,
                'vehicle_ids' => [$v1->id, $v2->id],
            ]);

        $response->assertStatus(302);

        $inventory = Inventory::where('name', strtoupper('Inventory with vehicles'))->first();
        $this->assertNotNull($inventory, 'Inventory was not created; response: '.$response->getContent());

        $this->assertDatabaseHas('inventory_vehicle', [
            'inventory_id' => $inventory->getKey(),
            'vehicle_id' => $v1->getKey(),
        ]);

        $this->assertDatabaseHas('inventory_vehicle', [
            'inventory_id' => $inventory->getKey(),
            'vehicle_id' => $v2->getKey(),
        ]);
    }

    public function test_update_syncs_vehicles(): void
    {
        $admin = User::factory()->admin()->create();

        $v1 = Vehicle::factory()->create();
        $v2 = Vehicle::factory()->create();
        $v3 = Vehicle::factory()->create();

        $inventory = Inventory::factory()->create([
            'name' => 'Sync Inventory',
            'qty' => 1,
            'price' => 10,
        ]);

        // Attach v1 and v2 initially
        $inventory->vehicles()->attach([$v1->id, $v2->id]);

        // Update to only include v3
        $response = $this->actingAs($admin)
            ->post('/inventories/'.$inventory->getKey(), [
                'name' => 'Sync Inventory',
                'qty' => 1,
                'price' => 10,
                'vehicle_ids' => [$v3->id],
            ]);

        $response->assertStatus(302);

        // v1 and v2 should be removed, v3 should be present
        $this->assertDatabaseMissing('inventory_vehicle', [
            'inventory_id' => $inventory->getKey(),
            'vehicle_id' => $v1->getKey(),
        ]);

        $this->assertDatabaseMissing('inventory_vehicle', [
            'inventory_id' => $inventory->getKey(),
            'vehicle_id' => $v2->getKey(),
        ]);

        $this->assertDatabaseHas('inventory_vehicle', [
            'inventory_id' => $inventory->getKey(),
            'vehicle_id' => $v3->getKey(),
        ]);
    }
}
