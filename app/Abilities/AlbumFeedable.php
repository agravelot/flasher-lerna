<?php

namespace App\Abilities;

use Spatie\Feed\FeedItem;

trait AlbumFeedable
{
    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id($this->id)
            ->title($this->title)
            ->summary($this->meta_description)
            ->updated($this->updated_at)
            ->link(route('albums.show', ['slug' => $this->slug]))
            ->author($this->user->name);
    }
}
