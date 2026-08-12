<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Panel access, exercised over HTTP so the Filament middleware actually runs.
 *
 * This has to be an HTTP test. Mounting a page component directly with Livewire::test
 * skips the middleware stack entirely, which is why the resource suite passed while the
 * panel was returning 403 to every real request.
 *
 * The test environment is not 'local', so these assertions reflect what production does.
 */
class FilamentPanelAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_is_redirected_to_the_login_page(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_an_admin_can_open_the_panel(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get('/admin')->assertSuccessful();
    }

    /**
     * The one that matters. Every mobile app user is a row in this table, and under
     * Filament 2 they could all sign in at /admin and edit prayer times.
     */
    public function test_an_ordinary_app_user_cannot_open_the_panel(): void
    {
        $user = User::factory()->create(['role' => null]);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_an_unrecognised_role_cannot_open_the_panel(): void
    {
        $user = User::factory()->create(['role' => 'editor']);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_the_login_page_is_reachable(): void
    {
        $this->get('/admin/login')->assertSuccessful();
    }

    /**
     * Panel access must not leak into the API: a mobile user is still a fully valid API
     * consumer, and denying them the admin must not deny them their own account.
     */
    public function test_a_non_admin_can_still_use_the_api(): void
    {
        $user = User::factory()->create(['role' => null]);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/auth/me')
            ->assertSuccessful();
    }
}
