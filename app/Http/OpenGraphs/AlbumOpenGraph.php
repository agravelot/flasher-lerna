<?php

namespace App\Http\OpenGraphs;

use App\Models\Album;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Http\OpenGraphs\Contracts\OpenGraphable;
use App\Http\OpenGraphs\Contracts\ImagesOpenGraphable;
use App\Http\OpenGraphs\Contracts\ArticleOpenGraphable;

class AlbumOpenGraph implements OpenGraphable, ArticleOpenGraphable, ImagesOpenGraphable
{
    /**
     * @var Album
     */
    private $album;

    public function __construct(Album $album)
    {
        $this->album = $album;
    }

    public function author(): string
    {
        return $this->album->user->name;
    }

    public function tags(): Collection
    {
        return $this->album->categories()->pluck('name');
    }

    public function publishedAt(): string
    {
        return $this->album->published_at->toIso8601String();
    }

    public function updatedAt(): string
    {
        return $this->album->updated_at->toIso8601String();
    }

    public function images(): Collection
    {
        return $this->album->getMedia(Album::PICTURES_COLLECTION);
    }

    public function title(): string
    {
        return $this->album->title;
    }

    public function description(): ?string
    {
        return Str::limit($this->album->body, 150);
    }

    public function type(): string
    {
        return 'article';
    }
}
