<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehiclesCreateShowsUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_form_lists_users_for_assignment(): void
    {
        // Arrange: create a signed-in admin user and some assignable users
        /** @var \App\Models\User $currentUser */
        $currentUser = User::factory()->admin()->create();
        $this->actingAs($currentUser);
        $users = User::factory()->count(3)->create();

        // Act: visit create vehicle page (auth required)
        $response = $this->get(route('vehicles.create'));

        // Assert: each user's name is present in the response
        foreach ($users as $user) {
            // Blade escapes HTML entities (e.g. apostrophes), so use default escaping
            $response->assertSee($user->name);
        }
    }
}
