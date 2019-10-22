<?php

namespace App\Observers;

use App\Models\Album;
use App\Notifications\PublishedAlbum;
use Illuminate\Support\Facades\Notification;

class AlbumObserver extends ActivityObserverBase
{
    public function creating(Album $album): void
    {
        if ($album->user_id === null) {
            $album->user_id = auth()->id();
        }
    }

    public function created(Album $album): void
    {
        if ($this->hasBeenPublished($album)) {
            $users = $album->cosplayers()->whereHas('user')->get()
                ->pluck('user')->where('notify_on_album_published', true);
            Notification::send($users, new PublishedAlbum($album));
        }
    }

    public function updated(Album $album): void
    {
        if ($this->shouldBeNotified($album) && $this->hasBeenPublished($album)) {
            $users = $album->cosplayers()->whereHas('user')->get()
                ->pluck('user')->where('notify_on_album_published', true);
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
