<?php

namespace App\Http\Schemas;

use App\Models\Album;
use Spatie\SchemaOrg\Person;
use Spatie\SchemaOrg\Schema;

class AlbumSchema implements SchemaInterface
{
    /**
     * @var Album
     */
    private $album;

    public function __construct(Album $album)
    {
        $this->album = $album;
    }

    public function __toString(): string
    {
        $schema = Schema::imageGallery()
            ->name($this->album->name)
            ->keywords($this->album->categories->pluck('name')->toArray())
            ->isAccessibleForFree(true)
            ->primaryImageOfPage(optional($this->album->cover)->getUrl())
            ->specialty($this->album->categories->pluck('name')->toArray())
            ->author([
                (new Person())->name($this->album->user->name)
                    ->email('contact@jkanda.fr')
                    ->telephone('+33766648588')
                    ->url(url('/'))
                    ->jobTitle('Photographer'),
            ])
            ->dateCreated($this->album->created_at)
            ->dateModified($this->album->updated_at)
            ->datePublished($this->album->published_at)
            ->thumbnailUrl(optional($this->album->cover)->getUrl('thumb'));

        return $schema->toScript();
    }
}
