<?php

namespace App\Http\Schemas;

use App\Models\Media;
use Spatie\SchemaOrg\Person;
use Spatie\SchemaOrg\Schema;

class MediaSchema implements SchemaInterface
{
    /**
     * @var Media
     */
    private $media;

    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    public function __toString(): string
    {
        $author = (new Person())->name(settings()->get('app_name'))->url(url('/'));
        $schema = Schema::imageObject()
            ->thumbnailUrl($this->media->getUrl('thumb'))
            ->dateCreated($this->media->created_at)
            ->dateModified($this->media->updated_at)
            ->isAccessibleForFree(true)
            ->author($author)
            ->creator($author)

            ->copyrightYear($this->media->created_at->format('Y'))
            ->copyrightHolder($author)
            ->uploadDate($this->media->created_at)
            ->exifData('')
            ->fileFormat($this->media->mime_type);

        return $schema->toScript();
    }
}
