<?php

namespace Tests\Feature\Http\Controllers\Admin\SocialMedia;

use Tests\TestCase;
use App\Models\SocialMedia;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteSocialMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_a_socialMedia(): void
    {
        $this->actingAsAdmin();
        $socialMedia = factory(SocialMedia::class)->create();

        $response = $this->deleteSocialMedia($socialMedia);

        $response->assertStatus(204);
        $this->assertNull($socialMedia->fresh());
    }

    public function deleteSocialMedia(SocialMedia $socialMedia): TestResponse
    {
        return $this->json('delete', "/api/admin/social-medias/{$socialMedia->id}");
    }

    public function test_user_cannot_delete_a_socialMedia(): void
    {
        $this->actingAsUser();
        $socialMedia = factory(SocialMedia::class)->create();

        $response = $this->deleteSocialMedia($socialMedia);

        $response->assertStatus(403);
        $this->assertNotNull($socialMedia->fresh());
    }

    public function test_guest_cannot_delete_a_socialMedia(): void
    {
        $socialMedia = factory(SocialMedia::class)->create();

        $response = $this->deleteSocialMedia($socialMedia);

        $response->assertStatus(401);
        $this->assertNotNull($socialMedia->fresh());
    }
}
