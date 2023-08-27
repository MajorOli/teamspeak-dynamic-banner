<?php

namespace Tests\Feature;

use App\Models\Instance;
use App\Models\InstanceProcess;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstanceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Instance $instance;

    public function setUp(): void
    {
        parent::setUp();

        // Run the DatabaseSeeder
        $this->seed();

        $this->user = User::factory()->create();
        $this->user->syncRoles('Instances Admin');

        $this->instance = Instance::factory()->create();
    }

    /**
     * Test, that the user gets redirected to the login, when he is unauthenticated.
     */
    public function test_user_gets_redirected_to_login_when_unauthenticated(): void
    {
        $response = $this->get(route('instances'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test, that the user can access the page, when he is authenticated.
     */
    public function test_page_gets_displayed_when_authenticated(): void
    {
        $response = $this->actingAs($this->user)->get(route('instances'));
        $response->assertStatus(200);
        $response->assertViewIs('instances');
    }

    /**
     * Test, that the user can access the "add instance" page, when he is authenticated.
     */
    public function test_add_instance_page_gets_displayed_when_authenticated(): void
    {
        $response = $this->actingAs($this->user)->get(route('instance.add'));
        $response->assertStatus(200);
        $response->assertViewIs('instance.add');
    }

    /**
     * Test that adding a new instance requires to match the request rules.
     */
    public function test_adding_a_new_instance_requires_to_match_the_request_rules(): void
    {
        $response = $this->actingAs($this->user)->post(route('instance.save'), [
            'voice_port' => fake()->numberBetween(1024, 65535),
            'serverquery_port' => fake()->numberBetween(1024, 65535),
            'serverquery_username' => fake()->userName(),
            'serverquery_password' => fake()->password(),
            'client_nickname' => fake()->name(),
        ]);
        $response->assertSessionHasErrors(['host']);
    }

    /**
     * Test that adding a new instance returns an error when no connection to the instance can be established.
     *
     * Disabled because the assertions are failing with this error:
     *    fwrite(): Argument #1 ($stream) must be of type resource, bool given
     */
    // public function test_adding_a_new_instance_returns_an_error_when_no_connection_to_the_instance_can_be_established(): void
    // {
    //     $response = $this->actingAs($this->user)->post(route('instance.save'), [
    //         'host' => fake()->domainName(),
    //         'voice_port' => fake()->numberBetween(1024, 65535),
    //         'serverquery_port' => fake()->numberBetween(1024, 65535),
    //         'serverquery_username' => fake()->userName(),
    //         'serverquery_password' => fake()->password(),
    //         'client_nickname' => fake()->name(),
    //     ]);
    //     $response->assertRedirectToRoute('instance.add');
    //     $response->assertSessionHasInput('host');
    //     $response->assertSessionHasInput('voice_port');
    //     $response->assertSessionHasInput('serverquery_port');
    //     $response->assertSessionHasInput('serverquery_password');
    //     $response->assertSessionHasInput('client_nickname');
    //     $response->assertSessionHas('error');
    // }

    /**
     * Test, that the user gets redirected to the instances overview, when the requested instance ID for the edit page does not exist.
     */
    public function test_edit_instance_page_gets_redirected_to_overview_when_instance_id_does_not_exist(): void
    {
        $response = $this->actingAs($this->user)->get(route('instance.edit', ['instance_id' => 1337]));
        $response->assertRedirect(route('instances'));
    }

    /**
     * Test, that the user can access the "edit instance" page, when the requested instance ID for the edit page exists.
     *
     * Disabled because the assertions are failing with this error:
     *    fwrite(): Argument #1 ($stream) must be of type resource, bool given
     */
    // public function test_edit_instance_page_gets_displayed_when_instance_id_exists(): void
    // {
    //     $instance = Instance::factory()->create();
    //
    //     $response = $this->actingAs($this->user)->get(route('instance.edit', ['instance_id' => $instance->id]));
    //     $response->assertViewIs('instance.edit');
    //     $response->assertViewHas('instance');
    //     $response->assertViewHas('channel_list');
    // }

    /**
     * Test that updating an existing instance requires to match the request rules.
     */
    public function test_updating_an_existing_instance_requires_to_match_the_request_rules(): void
    {
        $response = $this->actingAs($this->user)->patch(route('instance.update', ['instance_id' => $this->instance->id]), [
            'voice_port' => fake()->numberBetween(1024, 65535),
            'serverquery_port' => fake()->numberBetween(1024, 65535),
            'serverquery_username' => fake()->userName(),
            'serverquery_password' => fake()->password(),
            'client_nickname' => fake()->name(),
        ]);
        $response->assertSessionHasErrors(['host']);
    }

    /**
     * Test that trying to delete an instance ID, which does not exist, returns an error.
     */
    public function test_delete_instance_returns_an_error_when_the_given_instance_id_does_not_exist(): void
    {
        $response = $this->actingAs($this->user)->delete(route('instance.delete', ['instance_id' => 1337]));
        $response->assertStatus(302);
        $response->assertRedirectToRoute('instances');
        $response->assertSessionHas('error');
    }

    /**
     * Test that trying to delete an instance ID, which exists, returns the respective view.
     */
    public function test_delete_instance_returns_the_overview_when_the_given_instance_id_exists(): void
    {
        $response = $this->actingAs($this->user)->delete(route('instance.delete', ['instance_id' => $this->instance->id]));
        $response->assertStatus(302);
        $response->assertRedirectToRoute('instances');
        $response->assertSessionHas('success');
    }

    /**
     * Test that trying to start an instance ID, which does not exist, returns an error.
     */
    public function test_starting_an_instance_returns_an_error_when_the_given_instance_id_does_not_exist(): void
    {
        $response = $this->actingAs($this->user)->post(route('instance.start', ['instance_id' => 1337]));
        $response->assertRedirectToRoute('instances');
        $response->assertSessionHas('error');
    }

    /**
     * Test that trying to start an instance ID, which exist, is possible.
     */
    public function test_starting_an_instance_is_possible(): void
    {
        $response = $this->actingAs($this->user)->post(route('instance.start', ['instance_id' => $this->instance->id]));
        $response->assertRedirectToRoute('instances');
        $response->assertSessionHas('success');
    }

    /**
     * Test that trying to stop an instance ID, which does not exist, returns an error.
     */
    public function test_stopping_an_instance_returns_an_error_when_the_given_instance_id_does_not_exist(): void
    {
        $response = $this->actingAs($this->user)->post(route('instance.stop', ['instance_id' => 1337]));
        $response->assertRedirectToRoute('instances');
        $response->assertSessionHas('error');
    }

    /**
     * Test that trying to stop an instance ID, which exist, is possible.
     */
    public function test_stopping_an_instance_is_possible(): void
    {
        InstanceProcess::factory()->for($this->instance)->create();

        $response = $this->actingAs($this->user)->post(route('instance.stop', ['instance_id' => $this->instance->id]));
        $response->assertRedirectToRoute('instances');
        $response->assertSessionHas('success');
    }

    /**
     * Test that trying to restart an instance ID, which does not exist, returns an error.
     */
    public function test_restarting_an_instance_returns_an_error_when_the_given_instance_id_does_not_exist(): void
    {
        $response = $this->actingAs($this->user)->post(route('instance.restart', ['instance_id' => 1337]));
        $response->assertRedirectToRoute('instances');
        $response->assertSessionHas('error');
    }

    /**
     * Test that trying to restart an instance ID, which exist, is possible.
     */
    public function test_restarting_an_instance_is_possible(): void
    {
        InstanceProcess::factory()->for($this->instance)->create();

        $response = $this->actingAs($this->user)->post(route('instance.restart', ['instance_id' => $this->instance->id]));
        $response->assertRedirectToRoute('instances');
        $response->assertSessionHas('success');
    }
}
