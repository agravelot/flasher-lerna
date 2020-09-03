<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Api\Admin\MediaOrdering;

use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class MediaOrderingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_order_media_and_first_will_in_cover(): void
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->states(['withMedias'])->create();

        $reversedOrder = $album->getMedia(Album::PICTURES_COLLECTION)->reverse()->pluck('id')->toArray();
        $newCoverId = $reversedOrder[0];
        $response = $this->setMediaOrdering($album->slug, $reversedOrder);

        $response->assertOk();
        $this->assertSame(
            $reversedOrder,
            $album->refresh()->getMedia(Album::PICTURES_COLLECTION)->pluck('id')->toArray()
        );
        $this->assertSame($album->refresh()->cover->id, $newCoverId);
    }

    public function test_admin_can_only_order_media_of_this_album(): void
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->create();
        /** @var Album $albumWithMedia */
        $albumWithMedia = factory(Album::class)->states(['withMedias'])->create();

        $mediaIds = $albumWithMedia->getMedia(Album::PICTURES_COLLECTION)->pluck('id')->toArray();
        $response = $this->setMediaOrdering($album->slug, $mediaIds);

        $response->assertStatus(422);
        foreach ($album->media as $key => $media) {
            $response->assertJsonValidationErrors('media_ids.'.$key);
        }
    }

    public function test_admin_cannot_order_empty_media_ids_list(): void
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->create();

        $response = $this->setMediaOrdering($album->slug, []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('media_ids');
    }

    public function test_user_can_not_order_media(): void
    {
        $this->actingAsUser();
        /** @var Album $album */
        $album = factory(Album::class)->states(['withMedias'])->create();

        $response = $this->setMediaOrdering($album->slug, []);

        $response->assertStatus(403);
    }

    public function test_guest_can_not_order_media(): void
    {
        /** @var Album $album */
        $album = factory(Album::class)->states(['withMedias'])->create();

        $response = $this->setMediaOrdering($album->slug, []);

        $response->assertUnauthorized();
    }

    private function setMediaOrdering(string $albumSlug, array $mediaIds): TestResponse
    {
        return $this->patchJson("/api/admin/albums/{$albumSlug}/media-ordering", [
            'media_ids' => $mediaIds,
        ]);
    }
}
