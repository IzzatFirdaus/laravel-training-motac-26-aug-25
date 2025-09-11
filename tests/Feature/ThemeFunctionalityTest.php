<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ThemeFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the layout includes theme toggle button
     */
    public function test_layout_includes_theme_toggle()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('id="theme-toggle"', false);
        $response->assertSee('id="theme-toggle-icon"', false);
        $response->assertSee('id="theme-toggle-label"', false);
    }

    /**
     * Test that the HTML has proper data-theme attributes
     */
    public function test_html_has_theme_attributes()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('data-theme="light"', false);
    }

    /**
     * Test that MYDS CSS classes are present
     */
    public function test_myds_css_classes_present()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('class="theme-transition"', false);
        $response->assertSee('myds-nav', false);
        $response->assertSee('myds-btn', false);
    }

    /**
     * Test that theme toggle has proper ARIA attributes
     */
    public function test_theme_toggle_accessibility()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('aria-label="Tukar kepada tema gelap"', false);
        $response->assertSee('aria-pressed="false"', false);
        $response->assertSee('data-test="theme-toggle"', false);
    }

    /**
     * Test that Bootstrap Icons are properly loaded
     */
    public function test_bootstrap_icons_loaded()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('bi-sun-fill', false);
    }

    /**
     * Test that navigation includes proper MYDS classes and accessibility
     */
    public function test_navigation_myds_compliance()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('role="navigation"', false);
        $response->assertSee('aria-label="Navigasi utama"', false);
        $response->assertSee('myds-skip-link', false);
        $response->assertSee('myds-dropdown', false);
    }
}
