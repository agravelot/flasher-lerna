<?php

declare(strict_types=1);

namespace App\Abilities;

use Spatie\Feed\FeedItem;

trait AlbumFeedable
{
    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id($this->slug)
            ->title($this->title)
            ->summary($this->meta_description)
            ->updated($this->updated_at)
            ->link(route('albums.show', ['album' => $this]))
            ->author('JKanda');
    }
}
