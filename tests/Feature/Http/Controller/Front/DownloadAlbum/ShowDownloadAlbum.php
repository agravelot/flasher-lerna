<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Http\Controller\Front\DownloadAlbum;

use App\Models\Album;
use App\Models\Cosplayer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;

class ShowDownloadAlbum extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_a_published_album()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->states(['published', 'withMedias'])->create();

        $response = $this->get('/download-albums/' . $album->slug);

        $this->assertInstanceOf(StreamedResponse::class, $response->baseResponse);
    }

    public function test_admin_can_download_a_unpublished_album()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->states(['published'])->create();

        $response = $this->get('/download-albums/' . $album->slug);

        $this->assertInstanceOf(StreamedResponse::class, $response->baseResponse);
    }

    public function test_user_present_as_a_cosplayer_in_a_album_can_download_it()
    {
        $user = factory(User::class)->create();
        $cosplayer = factory(Cosplayer::class)->create(['user_id' => $user->id]);
        $this->actingAs($user);
        $album = factory(Album::class)->states(['published'])->create();
        $album->cosplayers()->attach($cosplayer);

        $response = $this->get('/download-albums/' . $album->slug);

        $response->assertStatus(200);
        $this->assertInstanceOf(StreamedResponse::class, $response->baseResponse);
    }

    public function test_user_present_as_a_cosplayer_in_a_album_can_not_download_it_if_not_published()
    {
        $user = factory(User::class)->create();
        $cosplayer = factory(Cosplayer::class)->create(['user_id' => $user->id]);
        $this->actingAs($user);
        $album = factory(Album::class)->states(['unpublished'])->create();
        $album->cosplayers()->attach($cosplayer);

        $response = $this->get('/download-albums/' . $album->slug);

        $response->assertStatus(403);
        $this->assertNotInstanceOf(StreamedResponse::class, $response->baseResponse);
    }

    public function test_user_non_present_as_a_cosplayer_in_a_album_cannot_download_it()
    {
        $this->actingAsUser();
        $album = factory(Album::class)->states(['published'])->create();

        $response = $this->get('/download-albums/' . $album->slug);

        $response->assertStatus(403);
        $this->assertNotInstanceOf(StreamedResponse::class, $response->baseResponse);
    }

    public function test_guest_cannot_download_a_album_and_are_redirected_to_the_login()
    {
        $album = factory(Album::class)->states(['published', 'withUser'])->create();

        $response = $this->get('/download-albums/' . $album->slug);

        $response->assertRedirect('/login');
        $this->followRedirects($response)
            ->assertStatus(200);
    }
}
