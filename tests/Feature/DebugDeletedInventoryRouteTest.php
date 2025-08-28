<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DebugDeletedInventoryRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_debug_route_response_for_admin()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/inventories/deleted');

        // Dump status and content for debugging; in CI this will still assert
        file_put_contents(__DIR__.'/debug_deleted_route.txt', "status=".$response->getStatusCode()."\n".substr($response->getContent(), 0, 1000));

        $this->assertNotEquals(404, $response->getStatusCode());
    }
}
