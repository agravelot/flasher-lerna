<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\SocialMedia;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class CreateSocialMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_not_view_create_page_for_a_social_media_and_is_redirected_to_login()
    {
        $response = $this->showSocialMediaCreate();

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    private function showSocialMediaCreate(): TestResponse
    {
        return $this->get('/admin/social-medias/create');
    }

    public function test_user_can_not_view_create_page_for_a_social_media()
    {
        $this->actingAsUser();

        $response = $this->showSocialMediaCreate();

        $response->assertStatus(403);
    }

    public function test_admin_can_view_create_page_for_a_social_media()
    {
        $this->actingAsAdmin();
        $this->disableExceptionHandling();

        $response = $this->showSocialMediaCreate();

        $response->assertStatus(200);
    }
}
