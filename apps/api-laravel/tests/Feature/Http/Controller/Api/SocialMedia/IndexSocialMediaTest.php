<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Api\SocialMedia;

use App\Models\SocialMedia;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class IndexSocialMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_should_only_see_active_social_medias(): void
    {
        $this->withoutExceptionHandling();
        $this->actingAsAdmin();
        $socialMedias = factory(SocialMedia::class, 4)->state('active')->create();
        factory(SocialMedia::class, 4)->state('non-active')->create();

        $response = $this->getSocialMedias();

        $response->assertOk()->assertJsonCount(4, 'data');
        $this->assertJsonFragments($response, $socialMedias);
        $this->assertDatabaseCount('social_media', 8);
    }

    private function getSocialMedias(): TestResponse
    {
        return $this->json('get', '/api/social-medias');
    }

    private function assertJsonFragments(TestResponse $response, Collection $socialMedias): void
    {
        $socialMedias->each(static function (SocialMedia $socialMedia) use ($response): void {
            $response->assertJsonFragment([
                'name' => $socialMedia->name,
                'url' => $socialMedia->url,
                'active' => $socialMedia->active,
                'created_at' => $socialMedia->created_at,
                'updated_at' => $socialMedia->updated_at,
            ]);
        });
    }
}
