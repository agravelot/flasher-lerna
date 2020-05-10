<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Front\DownloadAlbum;

use App\Models\Album;
use App\Models\Cosplayer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;

class ShowDownloadAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_a_published_album(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->states(['published', 'withMedias'])->create();

        $response = $this->getDownloadAlbum($album);

        $this->assertInstanceOf(StreamedResponse::class, $response->baseResponse);
        $response->assertHeader('Content-Disposition', 'attachment; filename="'.$album->zip_file_name.'"');
    }

    public function test_admin_can_download_a_unpublished_album(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->states(['unpublished'])->create();

        $response = $this->getDownloadAlbum($album);

        $this->assertInstanceOf(StreamedResponse::class, $response->baseResponse);
        $response->assertHeader('Content-Disposition', 'attachment; filename="'.$album->zip_file_name.'"');
    }

    public function test_user_present_as_a_cosplayer_in_a_album_can_download_it(): void
    {
        $this->actingAsUser();
        /** @var Cosplayer $cosplayer */
        $cosplayer = factory(Cosplayer::class)->create(['sso_id' => auth()->id()]);
        /** @var Album $album */
        $album = factory(Album::class)->states(['published', 'passwordLess'])->create();
        $album->cosplayers()->attach($cosplayer);
        $album->save();

        $response = $this->getDownloadAlbum($album);

        $response->assertOk();
        $this->assertInstanceOf(StreamedResponse::class, $response->baseResponse);
        $response->assertHeader('Content-Disposition', 'attachment; filename="'.$album->zip_file_name.'"');
    }

    public function test_user_present_as_a_cosplayer_in_a_album_can_not_download_it_if_not_published(): void
    {
        $user = factory(User::class)->make();
        $this->actingAs($user);
        $cosplayer = factory(Cosplayer::class)->create(['sso_id' => $user->id]);
        $album = factory(Album::class)->states(['unpublished'])->create();
        $album->cosplayers()->attach($cosplayer);

        $response = $this->get("/download-albums/{$album->slug}");

        $response->assertStatus(403);
        $this->assertNotInstanceOf(StreamedResponse::class, $response->baseResponse);
    }

    public function test_user_non_present_as_a_cosplayer_in_a_album_cannot_download_it(): void
    {
        $this->actingAsUser();
        $album = factory(Album::class)->states(['published'])->create();

        $response = $this->getDownloadAlbum($album);

        $response->assertStatus(403);
        $this->assertNotInstanceOf(StreamedResponse::class, $response->baseResponse);
    }

    public function test_guest_cannot_download_a_album_and_are_redirected_to_the_login(): void
    {
        $album = factory(Album::class)->states(['published', 'withUser'])->create();

        $response = $this->getDownloadAlbum($album);

        $response->assertRedirect(route('keycloak.login'));
    }

    private function getDownloadAlbum(Album $album): TestResponse
    {
        return $this->get('/download-albums/'.$album->slug);
    }
}
