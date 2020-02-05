<?php

namespace App\Models;

use App\Abilities\HasNameAsSlug;
use App\Abilities\HasSlugRouteKey;
use App\Traits\ClearsResponseCache;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\UploadedFile;
use Laravel\Scout\Searchable;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Category extends Model implements HasMedia
{
    use HasMediaTrait,
        Sluggable,
        SluggableScopeHelpers,
        HasSlugRouteKey,
        HasNameAsSlug,
        ClearsResponseCache,
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
     * @param  UploadedFile|null  $media
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

        return $this->addMedia($media)
            ->usingFileName("{$this->slug}.{$media->clientExtension()}")
            ->preservingOriginal()
            ->toMediaCollectionOnCloudDisk(self::COVER_COLLECTION);
    }

    /**
     * Defining the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::COVER_COLLECTION)
            ->acceptsFile(static function (File $file) {
                return mb_strpos($file->mimeType, 'image/') === 0;
            })
            ->singleFile();
    }

    /**
     * Register the media conversions.
     *
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion(self::RESPONSIVE_CONVERSION)
            ->optimize()
            ->withResponsiveImages()
            ->performOnCollections(self::COVER_COLLECTION);

        $this->addMediaConversion(self::THUMB_CONVERSION)
            ->width(400)
            ->optimize()
            ->performOnCollections(self::COVER_COLLECTION);
    }

    /**
     * Posts relationships.
     */
    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'categorizable')->latest();
    }

    /**
     * Albums relationships.
     */
    public function albums(): MorphToMany
    {
        return $this->morphedByMany(Album::class, 'categorizable')->latest();
    }

    /**
     * Album relationship, only published.
     */
    public function publishedAlbums(): MorphToMany
    {
        return $this->morphedByMany(PublicAlbum::class, 'categorizable')->latest();
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
            'thumb' => optional($this->cover)->getUrl('thumb'),
            'url' => route('categories.show', ['category' => $this]),
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return true;
    }
}
