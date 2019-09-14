<?php

namespace Tests\Feature\Http\Controller\Admin\SocialMedia;

use Tests\TestCase;
use App\Models\SocialMedia;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexSocialMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_index_page_with_multiple_social_medias()
    {
        $this->actingAsAdmin();
        $socialMedias = factory(SocialMedia::class, 5)->create();

        $response = $this->showSocialMediaIndex();

        $response->assertStatus(200)
            ->assertDontSee('Nothing to show');
        $socialMedias->each(static function (SocialMedia $socialMedia) use ($response) {
            $response->assertSee($socialMedia->name);
        });
    }

    private function showSocialMediaIndex(): TestResponse
    {
        return $this->get('/admin/social-medias');
    }

    public function test_admin_can_view_index_page_with_one_socialMedia()
    {
        $this->actingAsAdmin();
        $socialMedia = factory(SocialMedia::class)->create();

        $response = $this->showSocialMediaIndex();

        $response->assertStatus(200)
            ->assertSee($socialMedia->name)
            ->assertDontSee('Nothing to show');
    }

    public function test_admin_can_view_index_page_with_no_socialMedia()
    {
        $this->actingAsAdmin();

        $response = $this->showSocialMediaIndex();

        $response->assertStatus(200)
            ->assertSee('Nothing to show');
    }

    public function test_guest_can_not_view_index_page_for_a_socialMedia_and_is_redirected_to_login()
    {
        $response = $this->showSocialMediaIndex();

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_user_can_not_view_index_page_for_a_socialMedia()
    {
        $this->actingAsUser();

        $response = $this->showSocialMediaIndex();

        $response->assertStatus(403);
    }
}
