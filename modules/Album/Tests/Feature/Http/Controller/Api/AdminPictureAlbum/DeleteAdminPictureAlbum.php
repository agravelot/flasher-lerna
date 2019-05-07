<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Tests\Feature\Http\Controller\Api\AdminPictureAlbum;

use Tests\TestCase;
use App\Models\Album;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteAdminPictureAlbum extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_destroy_a_picture_of_an_album()
    {
        $this->actingAsAdmin();
        /* @var Album $album */
        $album = factory(Album::class)->state('withMedias')->create();
        $this->assertSame(15, $album->getMedia('pictures')->count());

        $response = $this->deleteAlbumPicture($album->slug, $album->getFirstMedia('pictures')->id);

        $this->assertSame(14, $album->fresh()->getMedia('pictures')->count());
        $response->assertStatus(403);
    }

    public function test_user_can_not_destroy_a_picture_to_an_album()
    {
        $this->actingAsUser();
        /* @var Album $album */
        $album = factory(Album::class)->state('withMedias')->create();
        $this->assertSame(15, $album->getMedia('pictures')->count());

        $response = $this->deleteAlbumPicture($album->slug, $album->getFirstMedia('pictures')->id);

        $this->assertSame(15, $album->fresh()->getMedia('pictures')->count());
        $response->assertStatus(403);
    }

    public function test_guest_can_not_destroy_a_picture_to_an_album()
    {
        /* @var Album $album */
        $album = factory(Album::class)->states(['withMedias', 'withUser'])->create();
        $this->assertSame(15, $album->getMedia('pictures')->count());

        $response = $this->deleteAlbumPicture($album->slug, $album->getFirstMedia('pictures')->id);

        $this->assertSame(15, $album->fresh()->getMedia('pictures')->count());
        $response->assertStatus(401);
    }

    private function deleteAlbumPicture(string $slug, int $mediaId): TestResponse
    {
        return $this->json('delete', "/api/admin/album-pictures/$slug", ['media_id' => $mediaId]);
    }
}
