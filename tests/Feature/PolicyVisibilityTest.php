<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyVisibilityTest extends TestCase
{
    use RefreshDatabase;

    // RefreshDatabase will run migrations; no need to call artisan migrate here.

    public function test_non_admin_can_view_indexes(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('users.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('vehicles.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('inventories.index'))
            ->assertOk();
    }

    public function test_non_admin_cannot_crud_others_records(): void
    {
        /** @var \App\Models\User $owner */
        $owner = User::factory()->create(['role' => 'user']);
        /** @var \App\Models\User $other */
        $other = User::factory()->create(['role' => 'user']);

        $inventory = Inventory::factory()->for($owner)->create();
        $vehicle = Vehicle::factory()->for($owner, 'owner')->create();

        // other user cannot view/edit/delete owner's inventory
        $this->actingAs($other)
            ->get(route('inventories.show', $inventory->id))
            ->assertForbidden();

        $this->actingAs($other)
            ->get(route('inventories.edit', $inventory->id))
            ->assertForbidden();

        $this->actingAs($other)
            ->post(route('inventories.destroy', $inventory->id))
            ->assertForbidden();

        // other user cannot view/edit/delete owner's vehicle
        $this->actingAs($other)
            ->get(route('vehicles.show', $vehicle->id))
            ->assertForbidden();

        $this->actingAs($other)
            ->get(route('vehicles.edit', $vehicle->id))
            ->assertForbidden();

        $this->actingAs($other)
            ->post(route('vehicles.destroy', $vehicle->id))
            ->assertForbidden();
    }

    public function test_admin_can_crud_any_records(): void
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        /** @var \App\Models\User $owner */
        $owner = User::factory()->create(['role' => 'user']);

        $inventory = Inventory::factory()->for($owner)->create();
        $vehicle = Vehicle::factory()->for($owner, 'owner')->create();

        $this->actingAs($admin)
            ->get(route('inventories.show', $inventory->id))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('inventories.edit', $inventory->id))
            ->assertOk();

        $this->actingAs($admin)
            ->post(route('inventories.destroy', $inventory->id))
            ->assertRedirect(route('inventories.index'));

        $this->actingAs($admin)
            ->get(route('vehicles.show', $vehicle->id))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('vehicles.edit', $vehicle->id))
            ->assertOk();

        $this->actingAs($admin)
            ->post(route('vehicles.destroy', $vehicle->id))
            ->assertRedirect(route('vehicles.index'));
    }
}
