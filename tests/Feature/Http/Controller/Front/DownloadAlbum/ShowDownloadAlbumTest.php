<?php

namespace Tests\Feature\Http\Controller\Front\DownloadAlbum;

use Tests\TestCase;
use App\Models\User;
use App\Models\Album;
use App\Models\Cosplayer;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ShowDownloadAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_a_published_album()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->states(['published', 'withMedias'])->create();

        $response = $this->getDownloadAlbum($album);

        $this->assertInstanceOf(StreamedResponse::class, $response->baseResponse);
        $response->assertHeader('Content-Disposition', 'attachment; filename="'.$album->zip_file_name.'"');
    }

    public function test_admin_can_download_a_unpublished_album()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->states(['unpublished'])->create();

        $response = $this->getDownloadAlbum($album);

        $this->assertInstanceOf(StreamedResponse::class, $response->baseResponse);
        $response->assertHeader('Content-Disposition', 'attachment; filename="'.$album->zip_file_name.'"');
    }

    public function test_user_present_as_a_cosplayer_in_a_album_can_download_it()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        /** @var Cosplayer $cosplayer */
        $cosplayer = factory(Cosplayer::class)->create(['user_id' => $user->id]);
        /** @var Album $album */
        $album = factory(Album::class)->states(['published', 'passwordLess'])->create();
        $album->cosplayers()->attach($cosplayer);
        $album->save();

        $response = $this->getDownloadAlbum($album);

        $response->assertOk();
        $this->assertInstanceOf(StreamedResponse::class, $response->baseResponse);
        $response->assertHeader('Content-Disposition', 'attachment; filename="'.$album->zip_file_name.'"');
    }

    public function test_user_present_as_a_cosplayer_in_a_album_can_not_download_it_if_not_published()
    {
        $user = factory(User::class)->create();
        $cosplayer = factory(Cosplayer::class)->create(['user_id' => $user->id]);
        $this->actingAs($user);
        $album = factory(Album::class)->states(['unpublished'])->create();
        $album->cosplayers()->attach($cosplayer);

        $response = $this->get("/download-albums/{$album->slug}");

        $response->assertStatus(403);
        $this->assertNotInstanceOf(StreamedResponse::class, $response->baseResponse);
    }

    public function test_user_non_present_as_a_cosplayer_in_a_album_cannot_download_it()
    {
        $this->actingAsUser();
        $album = factory(Album::class)->states(['published'])->create();

        $response = $this->getDownloadAlbum($album);

        $response->assertStatus(403);
        $this->assertNotInstanceOf(StreamedResponse::class, $response->baseResponse);
    }

    public function test_guest_cannot_download_a_album_and_are_redirected_to_the_login()
    {
        $album = factory(Album::class)->states(['published', 'withUser'])->create();

        $response = $this->getDownloadAlbum($album);

        $response->assertRedirect('/login');
        $this->followRedirects($response)
            ->assertOk();
    }

    private function getDownloadAlbum(Album $album): TestResponse
    {
        return $this->get('/download-albums/'.$album->slug);
    }
}
