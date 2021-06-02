<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Api\DownloadAlbum;

use App\Models\Album;
use App\Models\Cosplayer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;

class ShowDownloadAlbumTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cannot_download_without_signed_url(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->states(['published', 'withMedias'])->create();

        $response = $this->getJson('/api/download-albums/'.$album->slug);

        $response->assertStatus(403);
    }

    public function test_guest_can_download_with_generated_url_by_user(): void
    {
        $album = factory(Album::class)->states(['published', 'withMedias'])->create();

        $response = $this->get(URL::temporarySignedRoute('api.download-albums.show', now()->addHours(1), ['album' => $album]));

        $response->assertOk()->assertHeader('Content-Disposition', 'attachment; filename="'.$album->zip_file_name.'"');
        $this->assertInstanceOf(StreamedResponse::class, $response->baseResponse);
    }

    public function test_admin_can_download_a_published_album(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->states(['published', 'withMedias'])->create();

        $generateResponse = $this->generateDownloadLink($album)->assertOk();
        $response = $this->get($generateResponse->json('url'));

        $response->assertOk()->assertHeader('Content-Disposition', 'attachment; filename="'.$album->zip_file_name.'"');
        $this->assertInstanceOf(StreamedResponse::class, $response->baseResponse);
    }

    public function test_admin_can_download_a_unpublished_album(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->states(['unpublished'])->create();

        $generateResponse = $this->generateDownloadLink($album)->assertOk();
        $response = $this->get($generateResponse->json('url'));

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

        $generateResponse = $this->generateDownloadLink($album)->assertOk();
        $response = $this->get($generateResponse->json('url'));

        $response->assertOk();
        $this->assertInstanceOf(StreamedResponse::class, $response->baseResponse);
        $response->assertHeader('Content-Disposition', 'attachment; filename="'.$album->zip_file_name.'"');
    }

    public function test_user_present_as_a_cosplayer_in_a_album_can_not_generate_link_if_not_published(): void
    {
        $user = factory(User::class)->make();
        $this->actingAs($user, 'api');
        $cosplayer = factory(Cosplayer::class)->create(['sso_id' => $user->id]);
        $album = factory(Album::class)->states(['unpublished'])->create();
        $album->cosplayers()->attach($cosplayer);

        $generateResponse = $this->generateDownloadLink($album);

        $generateResponse->assertStatus(403);
        $this->assertNotInstanceOf(StreamedResponse::class, $generateResponse->baseResponse);
    }

    public function test_user_present_as_a_cosplayer_in_a_private_album_can_download_it(): void
    {
        $this->actingAsUser();
        /** @var Cosplayer $cosplayer */
        $cosplayer = factory(Cosplayer::class)->create(['sso_id' => auth()->id()]);
        /** @var Album $album */
        $album = factory(Album::class)->states(['published', 'password'])->create();
        $album->cosplayers()->attach($cosplayer);
        $album->save();

        $generateResponse = $this->generateDownloadLink($album)->assertOk();
        $response = $this->get($generateResponse->json('url'));

        $response->assertOk();
        $this->assertInstanceOf(StreamedResponse::class, $response->baseResponse);
        $response->assertHeader('Content-Disposition', 'attachment; filename="'.$album->zip_file_name.'"');
    }

    public function test_user_non_present_as_a_cosplayer_in_a_album_cannot_download_it(): void
    {
        $this->actingAsUser();
        $album = factory(Album::class)->states(['published'])->create();

        $this->generateDownloadLink($album)->assertStatus(403);
    }

    public function test_guest_cannot_download_a_album_and_are_redirected_to_the_login(): void
    {
        $album = factory(Album::class)->states(['published'])->create();

        $generateResponse = $this->generateDownloadLink($album);

        $generateResponse->assertStatus(401);
    }

    public function generateDownloadLink(Album $album): TestResponse
    {
        return $this->getJson('/api/generate-download-albums/'.$album->slug);
    }
}
