<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Instance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BannerTemplateTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Banner $banner;

    public function setUp(): void
    {
        parent::setUp();

        // Run the DatabaseSeeder
        $this->seed();

        $this->user = User::factory()->create();
        $this->user->syncRoles('Banners Admin');

        $this->banner = Banner::factory()->for(
            Instance::factory()->create()
        )->create();
    }

    /**
     * Test, that the user gets redirected to the login, when he is unauthenticated.
     */
    public function test_user_gets_redirected_to_login_when_unauthenticated(): void
    {
        $response = $this->get(route('banner.templates', ['banner_id' => $this->banner->id]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test, that the user can access the page, when he is authenticated.
     */
    public function test_page_gets_displayed_when_authenticated(): void
    {
        $response = $this->actingAs($this->user)->get(route('banner.templates', ['banner_id' => $this->banner->id]));
        $response->assertStatus(200);
        $response->assertViewIs('banner.template');
        $response->assertViewHas('banner');
        $response->assertViewHas('templates');
    }

    /**
     * Test, that the user can access the "add banner template" page, when he is authenticated.
     */
    public function test_add_banner_template_page_gets_displayed_when_authenticated(): void
    {
        $response = $this->actingAs($this->user)->get(route('banner.template.add', ['banner_id' => $this->banner->id]));
        $response->assertStatus(200);
        $response->assertViewIs('banner.template_add');
        $response->assertViewHas('templates');
    }

    /**
     * Test that adding a new template to a banner requires to match the request rules.
     */
    public function test_adding_a_new_template_to_a_banner_requires_to_match_the_request_rules(): void
    {
        $response = $this->actingAs($this->user)->post(route('banner.add.template'), [
            'banner_id' => $this->banner->id,
        ]);
        $response->assertSessionHasErrors(['template_id']);
    }

    /**
     * Test, that the user gets redirected to the banners overview, when the requested banner ID for the edit page does not exist.
     */
    public function test_edit_banner_page_gets_redirected_to_overview_when_banner_id_does_not_exist(): void
    {
        $response = $this->actingAs($this->user)->get(route('banner.templates', ['banner_id' => 1337]));
        $response->assertRedirect(route('banners'));
        $response->assertSessionHas('error');
    }

    /**
     * Test, that the user can access the "edit banner" page, when the requested banner ID for the edit page exists.
     */
    public function test_edit_banner_page_gets_displayed_when_banner_id_exists(): void
    {
        $response = $this->actingAs($this->user)->get(route('banner.templates', ['banner_id' => $this->banner->id]));
        $response->assertViewIs('banner.template');
        $response->assertViewHas('banner');
        $response->assertViewHas('templates');
    }
}
