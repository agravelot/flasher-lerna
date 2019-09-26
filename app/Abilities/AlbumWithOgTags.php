<?php

namespace App\Abilities;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;

trait AlbumWithOgTags
{
    public function author(): string
    {
        return $this->user->name;
    }

    public function tags(): Collection
    {
        return $this->categories()->pluck('name');
    }

    public function publishedAt(): string
    {
        return $this->published_at->toIso8601String();
    }

    public function updatedAt(): string
    {
        return $this->updated_at->toIso8601String();
    }

    public function images(): Collection
    {
        return $this->getMedia(self::PICTURES_COLLECTION);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return Str::limit($this->body, 150);
    }

    public function type(): string
    {
        return 'article';
    }
}
