<?php

namespace Tests\Feature\Http\Controller\Api\AdminAlbum;

use Tests\TestCase;
use App\Models\Album;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShareAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_share_published_album()
    {
        Mail::fake();
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('published')->create();
        $contacts = collect([]);

        $response = $this->shareAlbum($album, $contacts);

        $response->assertStatus(201);
        $contacts->each(static function ($contact) use ($album) {
            Mail::assertSent(ShareAlbum::class, static function (Mailable $mail) use ($album, $contact) {
                return $mail->album->id === $album->id;
            });
            // Assert a message was sent to the given users...
            Mail::assertSent(ShareAlbum::class, static function (Mailable $mail) use ($album, $contact) {
                return $mail->hasTo($contact->email);
            });
        });
        Mail::assertSent(ShareAlbum::class, $contacts->count());
    }

    public function test_admin_can_not_share_unpublished_album()
    {
        Mail::fake();
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('unpublished')->create();

        $response = $this->shareAlbum($album, collect([]));

        $response->assertStatus(422);
        Mail::assertNothingSent();
    }

    public function test_admin_can_share_published_album_with_password()
    {
        Mail::fake();
        $this->actingAsAdmin();
        $album = factory(Album::class)->states(['published', 'password'])->create();
        $contacts = collect([]);

        $response = $this->shareAlbum($album, collect([]));

        $response->assertStatus(201);
        $contacts->each(static function ($contact) use ($album) {
            Mail::assertSent(ShareAlbum::class, static function (Mailable $mail) use ($album, $contact) {
                return $mail->album->id === $album->id;
            });
            // Assert a message was sent to the given users...
            Mail::assertSent(ShareAlbum::class, static function (Mailable $mail) use ($album, $contact) {
                return $mail->hasTo($contact->email);
            });
        });
        Mail::assertSent(ShareAlbum::class, $contacts->count());
    }

    public function test_user_can_not_share_album()
    {
        Mail::fake();
        $this->actingAsUser();
        $album = factory(Album::class)->state('unpublished')->create();

        $response = $this->shareAlbum($album, collect([]));

        $response->assertStatus(403);
        Mail::assertNothingSent();
    }

    public function test_guest_can_not_share_album()
    {
        Mail::fake();
        $this->actingAsUser();
        $album = factory(Album::class)->state('unpublished')->create();

        $response = $this->shareAlbum($album, collect([]));

        $response->assertStatus(401);
        Mail::assertNothingSent();
    }

    private function shareAlbum(Album $album, Collection $contacts, string $message = ''): TestResponse
    {
        return $this->json('post', "/api/admin/share-albums/{$album->slug}",
            compact('contacts', 'message')
        );
    }
}
