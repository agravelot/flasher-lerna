<?php

namespace Tests\Feature\Http\Controller\Api\AdminPictureAlbum;

use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class DeleteAdminPictureAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_destroy_a_picture_of_an_album(): void
    {
        $this->actingAsAdmin();
        /* @var Album $album */
        $album = factory(Album::class)->state('withMedias')->create();
        $this->assertSame(15,
            $album->getMedia(Album::PICTURES_COLLECTION)->count()
        );

        $response = $this->deleteAlbumPicture($album->slug,
            $album->getFirstMedia(Album::PICTURES_COLLECTION)->id
        );

        $this->assertSame(14,
            $album->fresh()->getMedia(Album::PICTURES_COLLECTION)->count()
        );
        $response->assertStatus(204);
    }

    public function test_user_can_not_destroy_a_picture_to_an_album(): void
    {
        $this->actingAsUser();
        /* @var Album $album */
        $album = factory(Album::class)->state('withMedias')->create();
        $this->assertSame(15,
            $album->getMedia(Album::PICTURES_COLLECTION)->count()
        );

        $response = $this->deleteAlbumPicture($album->slug,
            $album->getFirstMedia(Album::PICTURES_COLLECTION)->id
        );

        $this->assertSame(15,
            $album->fresh()->getMedia(Album::PICTURES_COLLECTION)->count()
        );
        $response->assertStatus(403);
    }

    public function test_guest_can_not_destroy_a_picture_to_an_album(): void
    {
        /* @var Album $album */
        $album = factory(Album::class)->states([
            'withMedias', 'withUser',
        ])->create();
        $this->assertSame(15,
            $album->getMedia(Album::PICTURES_COLLECTION)->count()
        );

        $response = $this->deleteAlbumPicture(
            $album->slug, $album->getFirstMedia(Album::PICTURES_COLLECTION)->id
        );

        $this->assertSame(15,
            $album->fresh()->getMedia(Album::PICTURES_COLLECTION)->count()
        );
        $response->assertStatus(401);
    }

    private function deleteAlbumPicture(string $slug, int $mediaId): TestResponse
    {
        return $this->json('delete', "/api/admin/album-pictures/$slug", [
            'media_id' => $mediaId,
        ]);
    }
}
