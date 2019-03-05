<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\SocialMedia;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\SocialMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class StoreSocialMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_a_socialMedia()
    {
        $this->actingAsAdmin();
        $socialMedia = factory(SocialMedia::class)->make();

        $response = $this->storeSocialMedia($socialMedia);

        $this->assertSame(1, SocialMedia::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/social-medias');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee($socialMedia->name)
            ->assertSee('Social media successfully added');
    }

    private function storeSocialMedia(SocialMedia $socialMedia): TestResponse
    {
        session()->setPreviousUrl('/admin/social-medias/create');

        return $this->post('/admin/social-medias', $socialMedia->toArray());
    }

    public function test_admin_can_not_create_two_social_medias_with_the_same_name_and_redirect_with_error()
    {
        $this->actingAsAdmin();
        $socialMedia = factory(SocialMedia::class)->create();
        $duplicateNameSocialMedia = factory(SocialMedia::class)->make(['name' => $socialMedia->name]);

        $response = $this->storeSocialMedia($duplicateNameSocialMedia);

        $this->assertSame(1, SocialMedia::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/social-medias/create');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee('The name has already been taken.');
    }

    public function test_user_can_not_store_a_socialMedia()
    {
        $this->actingAsUser();
        $socialMedia = factory(SocialMedia::class)->make();

        $response = $this->storeSocialMedia($socialMedia);

        $this->assertSame(0, SocialMedia::count());
        $response->assertStatus(403);
    }

    public function test_guest_can_not_store_a_socialMedia_and_is_redirected_to_login()
    {
        $socialMedia = factory(SocialMedia::class)->make();

        $response = $this->storeSocialMedia($socialMedia);

        $this->assertSame(0, SocialMedia::count());
        $response->assertStatus(302)
            ->assertRedirect('/login');
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
