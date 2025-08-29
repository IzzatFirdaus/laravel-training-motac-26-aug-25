<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_update_and_delete()
    {
        $owner = User::factory()->create(['role' => 'user']);
        $this->actingAs($owner);

        $inventory = Inventory::factory()->create(['user_id' => $owner->id]);

        $res = $this->get(route('inventories.edit', $inventory->id));
        $res->assertStatus(200);

        $res = $this->post(route('inventories.update', $inventory->id), ['name' => 'New name']);
        $res->assertRedirect();

        $res = $this->post(route('inventories.destroy', $inventory->id));
        $res->assertRedirect();
    }

    public function test_non_owner_cannot_edit_or_delete()
    {
        $owner = User::factory()->create(['role' => 'user']);
        $other = User::factory()->create(['role' => 'user']);
        $inventory = Inventory::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other);

        $res = $this->get(route('inventories.edit', $inventory->id));
        $res->assertStatus(403);

        $res = $this->post(route('inventories.update', $inventory->id), ['name' => 'X']);
        $res->assertStatus(403);

        $res = $this->post(route('inventories.destroy', $inventory->id));
        $res->assertStatus(403);
    }

    public function test_admin_can_edit_and_delete_any_inventory()
    {
        $owner = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $inventory = Inventory::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($admin);

        $res = $this->get(route('inventories.edit', $inventory->id));
        $res->assertStatus(200);

        $res = $this->post(route('inventories.update', $inventory->id), ['name' => 'Admin update']);
        $res->assertRedirect();

        $res = $this->post(route('inventories.destroy', $inventory->id));
        $res->assertRedirect();
    }
}
