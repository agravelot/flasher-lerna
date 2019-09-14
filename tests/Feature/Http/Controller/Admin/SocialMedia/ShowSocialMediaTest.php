<?php

namespace Modules\SocialMedia\Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;
use App\Models\SocialMedia;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowSocialMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_get_social_media(): void
    {
        $this->actingAsAdmin();
        /** @var SocialMedia $socialMedia */
        $socialMedia = factory(SocialMedia::class)->create();

        $response = $this->getSocialMedia($socialMedia);

        $response->assertOk();
        $response->assertJson([
           'data' => [
               'id' => $socialMedia->id,
               'name' => $socialMedia->name,
               'url' => $socialMedia->url,
               'color' => $socialMedia->color,
               'active' => $socialMedia->active,
               'created_at' => $socialMedia->created_at->toJSON(),
               'updated_at' => $socialMedia->updated_at->toJSON(),
           ],
        ]);
    }

    public function test_user_cannot_show_social_media(): void
    {
        $this->actingAsUser();
        /** @var SocialMedia $socialMedia */
        $socialMedia = factory(SocialMedia::class)->create();

        $response = $this->getSocialMedia($socialMedia);

        $this->assertSame(403, $response->getStatusCode());
    }

    public function test_guest_cannot_show_social_media(): void
    {
        /** @var SocialMedia $socialMedia */
        $socialMedia = factory(SocialMedia::class)->create();

        $response = $this->getSocialMedia($socialMedia);

        $response->assertUnauthorized();
    }

    public function getSocialMedia(SocialMedia $socialMedia): TestResponse
    {
        return $this->getJson("/api/admin/social-medias/{$socialMedia->id}");
    }
}
