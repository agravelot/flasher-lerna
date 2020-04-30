<?php

namespace App\Observers;

use App\Facades\Keycloak;
use App\Models\Album;
use App\Notifications\PublishedAlbum;
use Illuminate\Support\Facades\Notification;

class AlbumObserver
{
    public function creating(Album $album): void
    {
        $album->user_id = 1;
        $album->sso_id ??= auth()->id();
    }

    public function saved(Album $album): void
    {
        if ($this->shouldBeNotified($album) && $this->hasBeenPublished($album)) {
            $ssoIds = $album->cosplayers()->whereNotNull('sso_id')->get(['sso_id']);
            //->pluck('user')->where('notify_on_album_published', true);
            $users = [];
            foreach ($ssoIds as $ssoId) {
                $users[] = Keycloak::users()->find($ssoId);
            }
            Notification::send($users, new PublishedAlbum($album));
        }
    }

    private function shouldBeNotified(Album $album): bool
    {
        return $album->notify_users_on_published;
    }

    private function hasBeenPublished(Album $album): bool
    {
        $wasNotPublished = $album->getOriginal('published_at') === null;
        $isNowPublished = $album->published_at !== null;

        return $wasNotPublished && $isNowPublished;
    }
}
