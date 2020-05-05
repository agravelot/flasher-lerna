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
        $album->sso_id ??= auth()->user()->sub ?? auth()->user()->token->sub;
    }

    public function saved(Album $album): void
    {
        if ($this->shouldBeNotified($album) && $this->hasBeenPublished($album)) {
            $cosplayers = $album->cosplayers()->whereNotNull('sso_id')->get(['sso_id']);
            $users = [];
            foreach ($cosplayers as $cosplayer) {
                $users[] = Keycloak::users()->find($cosplayer->sso_id);
            }
            // TODO UserRepresentation is not notifiable
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
