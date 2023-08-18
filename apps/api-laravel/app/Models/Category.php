<?php

declare(strict_types=1);

namespace App\Models;

use App\Abilities\HasNameAsSlug;
use App\Abilities\HasSlugRouteKey;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\UploadedFile;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia,
        Sluggable,
        SluggableScopeHelpers,
        HasSlugRouteKey,
        HasNameAsSlug,
        Searchable;

    public const COVER_COLLECTION = 'cover';
    public const RESPONSIVE_CONVERSION = 'responsive';
    public const THUMB_CONVERSION = 'thumb';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name', 'slug', 'meta_description', 'description'];

    public function getCoverAttribute(): ?Media
    {
        return $this->getFirstMedia(self::COVER_COLLECTION);
    }

    /**
     * Add media to Category::COVER_COLLECTION collection.
     *
     * @param  File|UploadedFile|null  $media
     */
    public function setCover($media): ?Media
    {
        if ($media === null && $this->cover) {
            $this->cover->delete();

            return null;
        }

        if ($media === null) {
            return null;
        }

        [$width, $height] = getimagesize($media->getRealPath());

        return $this->addMedia($media)
            ->usingFileName("{$this->slug}.{$media->clientExtension()}")
            ->preservingOriginal()
            ->withCustomProperties(['width' => $width, 'height' => $height])
            ->toMediaCollectionOnCloudDisk(self::COVER_COLLECTION);
    }

    /**
     * Defining the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::COVER_COLLECTION)
         ->acceptsFile(static function (File $file) {
             return Str::startsWith($file->mimeType, 'image/');
         })
            ->singleFile();
    }

    /**
     * Albums relationships.
     */
    public function albums(): BelongsToMany
    {
        return $this->belongsToMany(Album::class)->latest();
    }

    /**
     * Album relationship, only published.
     */
    public function publishedAlbums(): BelongsToMany
    {
        return $this->belongsToMany(PublicAlbum::class)->latest();
    }

    public function searchableAs(): string
    {
        return 'categories-'.config('app.env');
    }

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'meta_description' => $this->meta_description,
            'cover' => optional($this->cover)->getUrl(),
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return true;
    }
}
