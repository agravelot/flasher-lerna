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

class UpdateSocialMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_a_social_media_with_the_same_data()
    {
        $this->actingAsAdmin();
        $socialMedia = factory(SocialMedia::class)->create();

        $response = $this->updateSocialMedia($socialMedia, $socialMedia->id);

        $this->assertSame(1, SocialMedia::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/social-medias');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee($socialMedia->name);
    }

    private function updateSocialMedia(SocialMedia $socialMedia, ?int $id = null): TestResponse
    {
        session()->setPreviousUrl('/admin/social-medias/' . $id . '/edit');

        return $this->patch('/admin/social-medias/' . $id, $socialMedia->toArray());
    }

    public function test_admin_can_not_update_a_social_media_with_another_social_media_name()
    {
        $this->actingAsAdmin();
        $socialMedias = factory(SocialMedia::class, 2)->create();
        $socialMedias->get(1)->name = $socialMedias->get(0)->name;

        $response = $this->updateSocialMedia($socialMedias->get(1), $socialMedias->get(1)->id);

        $this->assertSame(2, SocialMedia::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/social-medias/' . $socialMedias->get(1)->id . '/edit');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee($socialMedias->get(1)->name)
            ->assertSee($socialMedias->get(1)->description)
            ->assertSee('The name has already been taken.');
    }

    public function test_user_can_not_update_a_social_media()
    {
        $this->actingAsUser();
        $socialMedia = factory(SocialMedia::class)->create();
        $socialMediaUpdate = factory(SocialMedia::class)->make();

        $response = $this->updateSocialMedia($socialMediaUpdate, $socialMedia->id);

        $this->assertSame(1, SocialMedia::count());
        $this->assertSame($socialMedia->id, $socialMedia->fresh()->id);
        $this->assertSame($socialMedia->title, $socialMedia->fresh()->title);
        $this->assertSame($socialMedia->description, $socialMedia->fresh()->description);
        $response->assertStatus(403);
    }

    public function test_guest_can_not_update_a_social_media_and_is_redirected_to_login()
    {
        $socialMedia = factory(SocialMedia::class)->create();
        $socialMediaUpdate = factory(SocialMedia::class)->make();

        $response = $this->updateSocialMedia($socialMediaUpdate, $socialMedia->id);

        $this->assertSame(1, SocialMedia::count());
        $this->assertSame($socialMedia->id, $socialMedia->fresh()->id);
        $this->assertSame($socialMedia->title, $socialMedia->fresh()->title);
        $this->assertSame($socialMedia->description, $socialMedia->fresh()->description);
        $response->assertStatus(302)
            ->assertRedirect('/login');
    }

    protected function setUp()
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
