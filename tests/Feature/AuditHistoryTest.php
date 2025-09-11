<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditHistoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_admin_cannot_view_audit_history_component()
    {
        $user = User::factory()->create(['role' => 'user']);
        $target = User::factory()->create();

        $this->actingAs($user)
            ->get(route('users.edit', ['user' => $target->id]))
            ->assertForbidden();
    }

    /** @test */
    public function admin_can_view_audit_history_component()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $target = User::factory()->create();

        $this->actingAs($admin)
            ->get(route('users.edit', ['user' => $target->id]))
            ->assertStatus(200)
            ->assertSee('Change History');
    }
}
