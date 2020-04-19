<?php

namespace Tests\Feature\Http\Controller\Admin\SocialMedia;

use App\Models\SocialMedia;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class IndexSocialMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_index_socialMedias(): void
    {
        $this->actingAsAdmin();
        $socialMedias = factory(SocialMedia::class, 8)->create();

        $response = $this->getSocialMedias();

        $response->assertOk();
        $this->assertJsonFragments($response, $socialMedias);
    }

    private function getSocialMedias(): TestResponse
    {
        return $this->json('get', '/api/admin/social-medias');
    }

    private function assertJsonFragments(TestResponse $response, Collection $socialMedias): void
    {
        $socialMedias->each(static function (SocialMedia $socialMedia) use ($response) {
            $response->assertJsonFragment([
                'name' => $socialMedia->name,
                'url' => $socialMedia->url,
                'active' => $socialMedia->active,
                'created_at' => $socialMedia->created_at,
                'updated_at' => $socialMedia->updated_at,
            ]);
        });
    }

    public function test_user_cannot_index_socialMedias(): void
    {
        $this->actingAsUser();
        $socialMedias = factory(SocialMedia::class, 8)->create();

        $response = $this->getSocialMedias();

        $response->assertStatus(403);
    }

    public function testGuestCannotIndexSocialMedias(): void
    {
        $socialMedias = factory(SocialMedia::class, 8)->create();

        $response = $this->getSocialMedias();

        $response->assertStatus(401);
    }
}
