<?php

namespace Tests\Feature\API\v1;

use App\Models\Banner;
use App\Models\BannerConfiguration;
use App\Models\BannerTemplate;
use App\Models\Instance;
use App\Models\Template;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BannerTest extends TestCase
{
    use RefreshDatabase;

    protected Banner $banner;

    public function setUp(): void
    {
        parent::setUp();

        $this->banner = Banner::factory()->for(
            Instance::factory()->create()
        )->create();
    }

    /**
     * Ensure, that the API endpoint requires a banner ID as parameter.
     */
    public function test_api_requires_banner_id_parameter(): void
    {
        $this->expectExceptionMessageMatches('/Missing required parameter/i');
        $response = $this->get(route('api.banner'));
        $response->assertStatus(404);
    }

    /**
     * Ensure, that the API endpoint requires an existing banner ID as parameter.
     */
    public function test_api_requires_existing_banner_id_parameter(): void
    {
        $response = $this->get(route('api.banner', ['banner_id' => 'abc']));
        $response->assertSeeText('Invalid Banner ID in the URL.');
        $response->assertStatus(404);

        $response = $this->get(route('api.banner', ['banner_id' => 1337]));
        $response->assertSeeText('Invalid Banner ID in the URL.');
        $response->assertStatus(404);
    }

    /**
     * Ensure, that the API endpoint returns an error, when it has no linked templates at all.
     */
    public function test_api_returns_error_when_no_templates_are_linked(): void
    {
        $response = $this->get(route('api.banner', ['banner_id' => base_convert($this->banner->id, 10, 35)]));
        $response->assertSeeText('The banner does either not have any configured templates or all of them are disabled.');
        $response->assertStatus(401);
    }

    /**
     * Ensure, that the API endpoint returns an error, when the given banner template ID in the URL does not exist.
     */
    public function test_api_returns_error_when_banner_template_id_in_url_does_not_exist(): void
    {
        $banner_template = BannerTemplate::factory()
            ->for(
                $this->banner
            )->for(
                Template::factory()->create()
            )->create();
        $banner_template->enabled = true;
        $banner_template->save();

        $response = $this->get(route('api.banner', ['banner_id' => base_convert($this->banner->id, 10, 35), 'banner_template_id' => 1337]));
        $response->assertSeeText('Invalid Banner Template ID in the URL.');
        $response->assertStatus(404);
    }

    /**
     * Ensure, that the API endpoint returns an error, when the template has no configuration at all.
     */
    public function test_api_returns_error_when_the_template_has_no_configuration(): void
    {
        $banner_template = BannerTemplate::factory()
            ->for(
                $this->banner
            )->for(
                Template::factory()->create()
            )->create();
        $banner_template->enabled = true;
        $banner_template->save();

        $response = $this->get(route('api.banner', ['banner_id' => base_convert($this->banner->id, 10, 35)]));
        $response->assertSeeText('The template does not have any configurations. This seems wrong.');
        $response->assertStatus(500);
    }

    /**
     * Ensure, that the API endpoint returns an image with respective HTTP headers when everything is fine.
     */
    public function test_api_returns_an_image_with_respective_http_headers_when_everything_is_fine(): void
    {
        $banner_configuration = BannerConfiguration::factory()
            ->for(
                $banner_template = BannerTemplate::factory()
                    ->for(
                        $this->banner
                    )->for(
                        Template::factory()->create()
                    )->create(['enabled' => true])
            )->create();

        // Generate a temporary image to be able to test the API
        $absolut_upload_directory = public_path($banner_template->template->file_path_original);
        $image_file_path = $absolut_upload_directory.'/'.$banner_template->template->filename;
        $gd_image = imagecreate(1024, 300);
        imagecolorallocate($gd_image, 0, 0, 0);
        if (! file_exists($absolut_upload_directory)) {
            mkdir($absolut_upload_directory, 0777, true);
        }
        imagepng($gd_image, $image_file_path);

        // Temporary download a TTF fontfile to be able test the API
        Storage::disk('public')->put($banner_configuration->fontfile_path, file_get_contents('https://api.fontsource.org/v1/fonts/abel/latin-400-normal.ttf'));

        $response = $this->get(route('api.banner', ['banner_id' => base_convert($this->banner->id, 10, 35)]));
        $response->assertHeader('Cache-Control');
        $response->assertHeader('Expires', '-1');
        $response->assertHeader('ETag');
        $response->assertHeader('Last-Modified');
        $this->assertContains($response->headers->get('Content-Type'), ['image/png', 'image/jpeg']);
        $response->assertStatus(200);

        // Delete temporary files again
        Storage::disk('public')->delete($banner_configuration->fontfile_path);
        unlink($image_file_path);
    }
}
