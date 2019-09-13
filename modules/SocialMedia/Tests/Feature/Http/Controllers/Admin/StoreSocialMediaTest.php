<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\SocialMedia\Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;
use App\Models\SocialMedia;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreSocialMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_social_media(): void
    {
        $this->actingAsAdmin();
        /** @var SocialMedia $socialMedia */
        $socialMedia = factory(SocialMedia::class)->state('active')->make();

        $response = $this->storeSocialMedia($socialMedia);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertTrue(SocialMedia::latest()->first()->active);
        $this->assertSame(1, SocialMedia::count());
    }

    public function test_user_cannot_update_social_media(): void
    {
        $this->actingAsUser();
        /** @var SocialMedia $socialMedia */
        $socialMedia = factory(SocialMedia::class)->state('active')->make();

        $response = $this->storeSocialMedia($socialMedia);

        $this->assertSame(403, $response->getStatusCode());
        $this->assertSame(0, SocialMedia::count());
    }

    public function test_guest_cannot_update_social_media(): void
    {
        /** @var SocialMedia $socialMedia */
        $socialMedia = factory(SocialMedia::class)->state('active')->make();

        $socialMedia->active = false;
        $response = $this->storeSocialMedia($socialMedia);

        $response->assertUnauthorized();
        $this->assertSame(0, SocialMedia::count());
    }

    public function storeSocialMedia(SocialMedia $socialMedia): TestResponse
    {
        return $this->json('post', '/api/admin/social-medias', [
            'name' => $socialMedia->name,
            'url' => $socialMedia->url,
            'icon' => $socialMedia->icon,
            'color' => $socialMedia->color,
            'active' => $socialMedia->active,
        ]);
    }
}
