<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_admin_route(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('vehicles.create'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_route(): void
    {
        /** @var User $admin */
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $response = $this->get(route('vehicles.create'));
        $response->assertStatus(200);
    }
}
