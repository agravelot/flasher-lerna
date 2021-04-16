<?php

declare(strict_types=1);

namespace Tests\Unit\Notifications;

use App\Adapters\Keycloak\UserRepresentation;
use App\Facades\Keycloak;
use App\Models\Album;
use App\Models\Cosplayer;
use App\Models\User;
use App\Notifications\PublishedAlbum;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PublishedAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_updating_album_as_published_send_notification_to_cosplayers_related_to_an_user(): void
    {
        Notification::fake();
        $user = factory(User::class)->make();
        Keycloak::shouldReceive('users->find->toUser')->andReturn($user);
        /** @var Collection $cosplayers */
        $cosplayers = factory(Cosplayer::class, 5)->create(['sso_id' => $user->id]);
        /** @var Album $album */
        $album = factory(Album::class)->states(['unpublished'])->create(['sso_id' => $user->id]);
        Notification::assertNothingSent();

        $album->cosplayers()->sync($cosplayers);
        $album->update(['published_at' => Carbon::now()]);

        $users = $album->cosplayers->pluck('sso_id')->map(static fn (string $ssoId) => UserRepresentation::fromArray([
            'id' => $user->id,
            'email' => $user->email,
            'username' => $user->username,
            'emailVerified' => $user->email_verified,
            'enabled' => true,
            'attributes' => [
                'notify_on_album_published' => $user->notify_on_album_published,
            ],
        ]));

        Notification::assertSentTo(
            new AnonymousNotifiable(),
            PublishedAlbum::class,
            static function ($notification, $channels, $notifiable) use ($users) {
                return $notifiable->routes['mail'] === $users->map(
                    static fn (UserRepresentation $user) => $user->email
                )->toArray();
            }
        );
    }

    public function test_when_album_is_published_send_notification_to_cosplayers_related_to_an_user_and_ignore_others(): void
    {
        Notification::fake();
        $user = factory(User::class)->make();
        Keycloak::shouldReceive('users->find')->andReturn(UserRepresentation::fromArray([
            'id' => $user->id,
            'email' => $user->email,
            'username' => $user->username,
            'emailVerified' => $user->email_verified,
            'enabled' => true,
            'attributes' => [
                'notify_on_album_published' => $user->notify_on_album_published,
            ],
        ]));
        $cosplayerToNotify = factory(Cosplayer::class)->create(['sso_id' => $user->id]);
        factory(Cosplayer::class, 5)->create();
        /** @var Album $album */
        $album = factory(Album::class)->states(['unpublished'])->create();
        Notification::assertNothingSent();

        $album->cosplayers()->sync(Cosplayer::all());
        $album->update(['published_at' => Carbon::now()]);

        $user = Keycloak::users()->find($cosplayerToNotify->sso_id);
        Notification::assertSentTo(
            new AnonymousNotifiable(),
            PublishedAlbum::class,
            static function ($notification, $channels, $notifiable) use ($user) {
                return $notifiable->routes['mail'] === [$user->email];
            }
        );
    }

    public function test_when_album_is_published_do_not_send_notification_if_album_has_no_cosplayers_related_to_an_user(
    ): void {
        Notification::fake();
        $cosplayers = factory(Cosplayer::class, 5)->create();
        $album = factory(Album::class)->states(['published'])->create();

        Notification::assertNothingSent();
    }

    public function test_only_notify_if_user_allow_notification(): void
    {
        Notification::fake();
        /** @var User $user */
        $user = factory(User::class)->make(['notify_on_album_published' => false]);
        Keycloak::shouldReceive('users->find->toUser')->andReturn($user);
        $userRepresentation = new UserRepresentation();
        $userRepresentation->id = $user->id;

        /** @var Cosplayer $cosplayer */
        $cosplayer = factory(Cosplayer::class)->create(['sso_id' => $user->id]);
        /** @var Album $album */
        $album = factory(Album::class)->states(['unpublished'])->create();
        Notification::assertNothingSent();

        $album->cosplayers()->sync($cosplayer);
        $album->update(['published_at' => Carbon::now()]);

        Notification::assertTimesSent(0, PublishedAlbum::class);
    }

    public function test_only_notify_if_album_notification_is_enabled(): void
    {
        Notification::fake();
        /** @var User $user */
        $user = factory(User::class)->make();
        /** @var Cosplayer $cosplayer */
        $cosplayer = factory(Cosplayer::class)->create(['sso_id' => $user->id]);
        /** @var Album $album */
        $album = factory(Album::class)->states(['unpublished'])->create([
            'notify_users_on_published' => false,
        ]);
        Notification::assertNothingSent();

        $album->cosplayers()->sync($cosplayer);
        $album->update(['published_at' => Carbon::now()]);

        Notification::assertTimesSent(0, PublishedAlbum::class);
    }
}
