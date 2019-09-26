<?php

namespace App\Models;

use Eloquent;
use Spatie\MediaLibrary\File;
use Illuminate\Support\Carbon;
use App\Abilities\HasNameAsSlug;
use Illuminate\Http\UploadedFile;
use App\Abilities\HasSlugRouteKey;
use App\Traits\ClearsResponseCache;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\Collection;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Image\Exceptions\InvalidManipulation;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * App\Models\Category.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection|Album[] $albums
 * @property Collection|Post[] $posts
 * @method static Builder|Category findSimilarSlugs($attribute, $config, $slug)
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereDescription($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereName($value)
 * @method static Builder|Category whereSlug($value)
 * @method static Builder|Category whereUpdatedAt($value)
 * @mixin Eloquent
 * @property Collection|PublicAlbum[] $publishedAlbums
 * @property-read mixed $cover
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @property-read int|null $albums_count
 * @property-read int|null $media_count
 * @property-read int|null $posts_count
 * @property-read int|null $published_albums_count
 */
class Category extends Model implements HasMedia
{
    use HasMediaTrait, Sluggable, SluggableScopeHelpers, HasSlugRouteKey, HasNameAsSlug, ClearsResponseCache;

    public const COVER_COLLECTION = 'cover';
    public const RESPONSIVE_CONVERSION = 'responsive';
    public const THUMB_CONVERSION = 'thumb';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description'];

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
        return $this->morphedByMany(Post::class, 'categorizable');
    }

    /**
     * Albums relationships.
     */
    public function albums(): MorphToMany
    {
        return $this->morphedByMany(Album::class, 'categorizable');
    }

    /**
     * Album relationship, only published.
     */
    public function publishedAlbums(): MorphToMany
    {
        return $this->morphedByMany(PublicAlbum::class, 'categorizable');
    }
}
