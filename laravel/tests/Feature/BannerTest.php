<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Instance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BannerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        // Run the DatabaseSeeder
        $this->seed();

        $this->user = User::factory()->create();
        $this->user->syncRoles('Banners Admin');
    }

    /**
     * Test, that the user gets redirected to the login, when he is unauthenticated.
     */
    public function test_user_gets_redirected_to_login_when_unauthenticated(): void
    {
        $response = $this->get(route('banners'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test, that the user can access the page, when he is authenticated.
     */
    public function test_page_gets_displayed_when_authenticated(): void
    {
        $response = $this->actingAs($this->user)->get(route('banners'));
        $response->assertStatus(200);
        $response->assertViewIs('banners');
        $response->assertViewHas('banners');
    }

    /**
     * Test, that the user can access the "add banner" page, when he is authenticated.
     */
    public function test_add_banner_page_gets_displayed_when_authenticated(): void
    {
        $response = $this->actingAs($this->user)->get(route('banner.add'));
        $response->assertStatus(200);
        $response->assertViewIs('banner.add');
        $response->assertViewHas('instance_list');
    }

    /**
     * Test, that the user gets redirected to the banners overview, when the requested banner ID for the edit page does not exist.
     */
    public function test_edit_banner_page_gets_redirected_to_overview_when_banner_id_does_not_exist(): void
    {
        $response = $this->actingAs($this->user)->get(route('banner.edit', ['banner_id' => 1337]));
        $response->assertRedirect(route('banners'));
    }

    /**
     * Test, that the user can access the "edit banner" page, when the requested banner ID for the edit page exists.
     */
    public function test_edit_banner_page_gets_displayed_when_banner_id_exists(): void
    {
        $banner = Banner::factory()->for(
            Instance::factory()->create()
        )->create();

        $response = $this->actingAs($this->user)->get(route('banner.edit', ['banner_id' => $banner->id]));
        $response->assertViewIs('banner.edit');
        $response->assertViewHas('banner');
        $response->assertViewHas('instance_list');
        $response->assertViewHas('banner_configurations');
    }
}
