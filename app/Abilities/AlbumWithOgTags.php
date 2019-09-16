<?php

namespace App\Abilities;

trait AlbumWithOgTags
{
    public function author(): string
    {
        return $this->user->name;
    }

    public function tags(): \Illuminate\Support\Collection
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

    public function images(): \Illuminate\Support\Collection
    {
        return $this->getMedia(self::PICTURES_COLLECTION);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return '';
    }

    public function type(): string
    {
        return 'article';
    }
}
