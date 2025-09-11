<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_fetch_audits()
    {
        $user = User::factory()->create(['role' => 'user']);
        $target = User::factory()->create();

        $this->actingAs($user)
            ->getJson(route('users.audits', ['user' => $target->id]))
            ->assertForbidden();
    }

    public function test_admin_can_fetch_audits()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $target = User::factory()->create();

        // Create a fake audit record by updating the target
        $this->actingAs($admin);
        $target->name = 'Changed Name';
        $target->save();

        $this->actingAs($admin)
            ->getJson(route('users.audits', ['user' => $target->id]))
            ->assertOk()
            ->assertJsonStructure(['data', 'total', 'per_page', 'current_page', 'last_page']);
    }
}
