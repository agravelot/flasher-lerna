<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\Album;

use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class DeletePictureAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_a_picture_to_an_album()
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->states(['published', 'withMedias'])->create();
        $this->assertSame(15, $album->getMedia('pictures')->count());

        $response = $this->deleteAlbumPicture($album->slug, $album->getFirstMedia('pictures')->id);

        $this->assertSame(14, $album->fresh()->getMedia('pictures')->count());
        $response->assertStatus(204);
    }

    public function test_admin_can_not_delete_a_non_existent_picture_to_an_album()
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->states(['published', 'withMedias'])->create();
        $this->assertSame(15, $album->getMedia('pictures')->count());

        $response = $this->deleteAlbumPicture($album->slug, 10000);

        $this->assertSame(15, $album->fresh()->getMedia('pictures')->count());

        $response->assertStatus(422);
    }

    public function test_admin_can_not_delete_a_non_filled_picture_to_an_album()
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->states(['published', 'withMedias'])->create();
        $this->assertSame(15, $album->getMedia('pictures')->count());

        $response = $this->deleteAlbumPicture($album->slug);

        $this->assertSame(15, $album->fresh()->getMedia('pictures')->count());
        $response->assertStatus(422);
    }

    public function test_user_can_not_delete_a_picture_to_an_album()
    {
        $this->actingAsUser();
        /** @var Album $album */
        $album = factory(Album::class)->states(['published', 'withMedias'])->create();
        $this->assertSame(15, $album->getMedia('pictures')->count());

        $response = $this->deleteAlbumPicture($album->slug);

        $this->assertSame(15, $album->fresh()->getMedia('pictures')->count());
        $response->assertStatus(403);
    }

//    public function test_guest_can_not_delete_a_picture_to_an_album()
//    {
//        $album = factory(Album::class)->states(['published', 'withMedias'])->create();
//        $this->assertSame(15, $album->getMedia('pictures')->count());
//
//        $response = $this->deleteAlbumPicture($album->slug);
//
//        $this->assertSame(15, $album->fresh()->getMedia('pictures')->count());
//        $response->assertRedirect('/login');
//    }

    public function deleteAlbumPicture(string $albumSlug, int $mediaId = null, array $optional = []): TestResponse
    {
        session()->setPreviousUrl('/admin/albums');

        return $this->json('delete', '/api/admin/album-pictures/' . $albumSlug,
            array_merge(['media_id' => $mediaId], $optional)
        );
    }
}
