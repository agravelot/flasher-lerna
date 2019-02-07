<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\SocialMedia;

use App\Models\SocialMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class EditSocialMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_not_view_edit_page_for_a_socialMedia_and_is_redirected_to_login()
    {
        /** @var SocialMedia $socialMedia */
        $socialMedia = factory(SocialMedia::class)->create();

        $response = $this->showSocialMediaEdit($socialMedia->id);

        $response->assertStatus(302)
            ->assertRedirect('/login')
            ->assertDontSee($socialMedia->name);
    }

    private function showSocialMediaEdit(int $id): TestResponse
    {
        return $this->get('/admin/social-medias/' . $id . '/edit');
    }

    public function test_user_can_not_view_edit_page_for_a_socialMedia()
    {
        $this->actingAsUser();
        /** @var SocialMedia $socialMedia */
        $socialMedia = factory(SocialMedia::class)->create();

        $response = $this->showSocialMediaEdit($socialMedia->id);

        $response->assertStatus(403);
    }

    public function test_admin_can_view_edit_page_for_a_socialMedia()
    {
        $this->actingAsAdmin();
        /** @var SocialMedia $socialMedia */
        $socialMedia = factory(SocialMedia::class)->create();

        $response = $this->showSocialMediaEdit($socialMedia->id);

        $response->assertStatus(200)
            ->assertSee($socialMedia->name);
    }

    public function test_admin_can_not_edit_non_existent_socialMedia()
    {
        $this->actingAsAdmin();

        $response = $this->showSocialMediaEdit(42);

        $response->assertStatus(404);
    }
}
