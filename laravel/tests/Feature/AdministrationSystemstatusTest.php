<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdministrationSystemstatusTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        // Run the DatabaseSeeder
        $this->seed();

        $this->user = User::factory()->create();
        $this->user->syncRoles('System Status Viewer');
    }

    /**
     * Test, that the user gets redirected to the login, when he is unauthenticated.
     */
    public function test_user_gets_redirected_to_login_when_unauthenticated(): void
    {
        $response = $this->get(route('administration.systemstatus'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test, that the user can access the page, when he is authenticated.
     */
    public function test_page_gets_displayed_when_authenticated(): void
    {
        $response = $this->actingAs($this->user)->get(route('administration.systemstatus'));
        $response->assertStatus(200);
        $response->assertViewIs('administration.systemstatus');
    }

    /**
     * Test, that the systemstatus page can be displayed.
     */
    public function test_systemstatus_view_gets_displayed(): void
    {
        $response = $this->actingAs($this->user)->get(route('administration.systemstatus'));
        $response->assertStatus(200);
        $response->assertViewIs('administration.systemstatus');
    }
}
