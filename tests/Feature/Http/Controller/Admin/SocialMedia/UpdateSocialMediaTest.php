<?php

namespace Tests\Feature\Http\Controllers\Admin\SocialMedia;

use App\Models\SocialMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class UpdateSocialMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_social_media(): void
    {
        $this->actingAsAdmin();
        /** @var SocialMedia $socialMedia */
        $socialMedia = factory(SocialMedia::class)->state('active')->create();

        $socialMedia->active = false;
        $response = $this->updateSocialMedia($socialMedia);

        $response->assertOk();
        $this->assertFalse($socialMedia->fresh()->active);
        $this->assertSame(1, SocialMedia::count());
    }

    public function test_user_cannot_update_social_media(): void
    {
        $this->actingAsUser();
        /** @var SocialMedia $socialMedia */
        $socialMedia = factory(SocialMedia::class)->state('active')->create();

        $socialMedia->active = false;
        $response = $this->updateSocialMedia($socialMedia);

        $this->assertSame(403, $response->getStatusCode());
        $this->assertSame(1, SocialMedia::count());
    }

    public function test_guest_cannot_update_social_media(): void
    {
        /** @var SocialMedia $socialMedia */
        $socialMedia = factory(SocialMedia::class)->state('active')->create();

        $socialMedia->active = false;
        $response = $this->updateSocialMedia($socialMedia);

        $response->assertUnauthorized();
        $this->assertSame(1, SocialMedia::count());
    }

    public function updateSocialMedia(SocialMedia $socialMedia): TestResponse
    {
        return $this->json('patch', "/api/admin/social-medias/{$socialMedia->id}", [
            'name' => $socialMedia->name,
            'url' => $socialMedia->url,
            'icon' => $socialMedia->icon,
            'color' => $socialMedia->color,
            'active' => $socialMedia->active,
        ]);
    }
}
