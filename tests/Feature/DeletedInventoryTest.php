<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeletedInventoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \App\Models\User
     */
    protected $admin;

    /**
     * @var \App\Models\User
     */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
        $this->user = User::factory()->create();
    }

    public function test_admin_can_access_deleted_inventories_index(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/inventories/deleted');

        $response->assertStatus(200);
        $response->assertViewIs('inventories.deleted.index');
    }

    public function test_regular_user_cannot_access_deleted_inventories_index(): void
    {
        $response = $this->actingAs($this->user)
            ->get('/inventories/deleted');

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_deleted_inventories_index(): void
    {
        $response = $this->get('/inventories/deleted');

        $response->assertRedirect('/login');
    }

    public function test_deleted_inventories_page_displays_soft_deleted_inventories(): void
    {
        // Create some inventories
        $inventory1 = Inventory::factory()->create(['name' => 'Test Inventory 1']);
        $inventory2 = Inventory::factory()->create(['name' => 'Test Inventory 2']);
        $inventory3 = Inventory::factory()->create(['name' => 'Test Inventory 3']);

        // Soft delete some inventories
        $inventory1->delete();
        $inventory2->delete();

        $response = $this->actingAs($this->admin)
            ->get('/inventories/deleted');

        $response->assertStatus(200);
        $response->assertSee('TEST INVENTORY 1'); // Name is uppercase in model
        $response->assertSee('TEST INVENTORY 2');
        $response->assertDontSee('TEST INVENTORY 3'); // Not deleted
    }

    public function test_admin_can_restore_deleted_inventory(): void
    {
        $inventory = Inventory::factory()->create(['name' => 'Test Inventory']);
        $inventory->delete();

        $this->assertSoftDeleted('inventories', ['id' => $inventory->id]);

        $response = $this->actingAs($this->admin)
            ->post("/inventories/{$inventory->id}/restore");

        $response->assertRedirect('/inventories/deleted');
        $response->assertSessionHas('toast', 'Inventori telah dipulihkan.');

        $this->assertDatabaseHas('inventories', [
            'id' => $inventory->id,
            'deleted_at' => null,
        ]);
    }

    public function test_regular_user_cannot_restore_deleted_inventory(): void
    {
        $inventory = Inventory::factory()->create(['name' => 'Test Inventory']);
        $inventory->delete();

        $response = $this->actingAs($this->user)
            ->post("/inventories/{$inventory->id}/restore");

        $response->assertStatus(403);
    }

    public function test_admin_can_force_delete_inventory(): void
    {
        $inventory = Inventory::factory()->create(['name' => 'Test Inventory']);
        $inventory->delete();

        $response = $this->actingAs($this->admin)
            ->post("/inventories/{$inventory->id}/force-delete");

        $response->assertRedirect('/inventories/deleted');
        $response->assertSessionHas('toast', 'Inventori telah dipadam secara kekal.');

        $this->assertDatabaseMissing('inventories', ['id' => $inventory->id]);
    }

    public function test_regular_user_cannot_force_delete_inventory(): void
    {
        $inventory = Inventory::factory()->create(['name' => 'Test Inventory']);
        $inventory->delete();

        $response = $this->actingAs($this->user)
            ->post("/inventories/{$inventory->id}/force-delete");

        $response->assertStatus(403);
    }

    public function test_search_functionality_in_deleted_inventories(): void
    {
        // Create inventories with different names
    $inventory1 = Inventory::factory()->create(['name' => 'Laptop Dell', 'description' => '']);
    $inventory2 = Inventory::factory()->create(['name' => 'Mouse Logitech', 'description' => '']);
    $inventory3 = Inventory::factory()->create(['name' => 'Keyboard Gaming', 'description' => '']);

        // Delete all inventories
        $inventory1->delete();
        $inventory2->delete();
        $inventory3->delete();

        // Search for "laptop"
        $response = $this->actingAs($this->admin)
            ->get('/inventories/deleted?search=laptop');

        $response->assertStatus(200);
        $response->assertSee('LAPTOP DELL'); // Name is uppercase in model
        $response->assertDontSee('MOUSE LOGITECH');
        $response->assertDontSee('KEYBOARD GAMING');
    }

    public function test_pagination_works_with_deleted_inventories(): void
    {
        // Create more than 10 inventories to test pagination
        $inventories = Inventory::factory()->count(15)->create();

        // Delete all inventories
        $inventories->each(function ($inventory) {
            $inventory->delete();
        });

        $response = $this->actingAs($this->admin)
            ->get('/inventories/deleted');

        $response->assertStatus(200);
        // Check that pagination links are present
        $response->assertSee('pagination');
    }

    public function test_empty_state_displays_when_no_deleted_inventories(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/inventories/deleted');

        $response->assertStatus(200);
        $response->assertSee('Tiada inventori yang dipadam dijumpai.');
    }

    public function test_policy_authorization_for_restore_and_force_delete(): void
    {
        $inventory = Inventory::factory()->create(['user_id' => $this->user->getKey()]);
        $inventory->delete();

        // Test that only admin can restore
        $this->assertTrue($this->admin->can('restore', $inventory));
        $this->assertFalse($this->user->can('restore', $inventory));

        // Test that only admin can force delete
        $this->assertTrue($this->admin->can('forceDelete', $inventory));
        $this->assertFalse($this->user->can('forceDelete', $inventory));
    }
}
