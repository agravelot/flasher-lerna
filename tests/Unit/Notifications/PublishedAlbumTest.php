<?php

namespace Tests\Unit\Models;

use App\Models\Album;
use App\Models\Cosplayer;
use App\Notifications\PublishedAlbum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PublishedAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_when_album_is_published_send_notification_to_cosplayers_related_to_an_user()
    {
        Notification::fake();
        /** @var Collection $cosplayers */
        $cosplayers = factory(Cosplayer::class, 5)->state('withUser')->create();
        /** @var Album $album */
        $album = factory(Album::class)->states(['unpublished', 'withUser'])->create();
        Notification::assertNothingSent();

        $album->cosplayers()->sync($cosplayers);
        $album->update(['published_at' => null]);

        $users = $album->cosplayers->pluck('user');
        Notification::assertTimesSent(5, PublishedAlbum::class);
        Notification::assertSentTo($users, PublishedAlbum::class);
    }

    public function test_when_album_is_published_send_notification_to_cosplayers_related_to_an_user_and_ignore_others()
    {
        Notification::fake();
        $cosplayers = collect([
            factory(Cosplayer::class)->state('withUser')->create(),
            factory(Cosplayer::class)->create(),
        ]);
        /** @var Album $album */
        $album = factory(Album::class)->states(['published', 'withUser'])->create();

        $users = $cosplayers->pluck('user');

        Notification::assertTimesSent(1, PublishedAlbum::class);
        Notification::assertSentTo($users, PublishedAlbum::class);
    }

    public function test_when_album_is_published_do_not_send_notification_if_album_has_no_cosplayers_related_to_an_user()
    {
        Notification::fake();
        $cosplayers = factory(Cosplayer::class, 5)->create();
        $album = factory(Album::class)->states(['published', 'withUser'])->create();

        Notification::assertNothingSent();
    }
}
