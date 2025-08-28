<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimpleDeletedInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_deleted_inventory_route_exists(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->get('/inventories/deleted');

        // Just check if the route exists and doesn't return 404
        $this->assertNotEquals(404, $response->getStatusCode(), 'Route /inventories/deleted should exist');
    }
}
