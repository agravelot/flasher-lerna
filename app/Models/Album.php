<?php

namespace App\Models;

use Eloquent;
use Spatie\Feed\Feedable;
use Spatie\MediaLibrary\File;
use Illuminate\Support\Carbon;
use App\Abilities\AlbumFeedable;
use App\Abilities\HasTitleAsSlug;
use App\Abilities\AlbumWithOgTags;
use App\Abilities\HasSlugRouteKey;
use Illuminate\Support\HtmlString;
use App\Traits\ClearsResponseCache;
use Spatie\MediaLibrary\Models\Media;
use App\Models\Contracts\OpenGraphable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Contracts\ImagesOpenGraphable;
use App\Models\Contracts\ArticleOpenGraphable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Image\Exceptions\InvalidManipulation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Album.
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string|null $body
 * @property string|null $published_at
 * @property string|null $password
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection|Category[] $categories
 * @property Collection|Comment[] $comments
 * @property Collection|Cosplayer[] $cosplayers
 * @property Collection|Media[] $media
 * @property User $user
 * @method static Builder|Album findSimilarSlugs($attribute, $config, $slug)
 * @method static Builder|Album newModelQuery()
 * @method static Builder|Album newQuery()
 * @method static Builder|Album query()
 * @method static Builder|Album whereBody($value)
 * @method static Builder|Album whereCreatedAt($value)
 * @method static Builder|Album whereId($value)
 * @method static Builder|Album wherePassword($value)
 * @method static Builder|Album wherePublishedAt($value)
 * @method static Builder|Album whereSlug($value)
 * @method static Builder|Album whereTitle($value)
 * @method static Builder|Album whereUpdatedAt($value)
 * @method static Builder|Album whereUserId($value)
 * @mixin Eloquent
 * @property int $private
 * @method static Builder|Album wherePrivate($value)
 * @method static Builder|Album public ()
 * @property mixed $cover
 * @property-read mixed $cover_responsive
 * @property-read int|null $categories_count
 * @property-read int|null $comments_count
 * @property-read int|null $cosplayers_count
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album public()
 * @property-read mixed $zip_file_name
 */
class Album extends Model implements HasMedia, OpenGraphable, ArticleOpenGraphable, ImagesOpenGraphable, Feedable
{
    use Sluggable, SluggableScopeHelpers, HasMediaTrait, HasSlugRouteKey, HasTitleAsSlug, ClearsResponseCache, AlbumWithOgTags, AlbumFeedable;

    public const PICTURES_COLLECTION = 'pictures';
    public const RESPONSIVE_PICTURES_CONVERSION = 'responsive';

    protected $with = ['media'];

    protected $dates = [
        'published_at', 'updated_at', 'created_at',
    ];

    protected $casts = ['private' => 'bool'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'body',
        'published_at',
        'user_id',
        'private',
    ];

    public function getZipFileNameAttribute(): string
    {
        return $this->title.'.zip';
    }

    public function getCoverAttribute(): ?Media
    {
        return $this->media()->where('collection_name', self::PICTURES_COLLECTION)
            ->cursor()->first();
    }

    public function getCoverResponsiveAttribute(): ?HtmlString
    {
        return optional($this->cover, static function (Media $media) {
            return $media(self::RESPONSIVE_PICTURES_CONVERSION);
        });
    }

    /**
     * Scope for public albums.
     */
    public function scopePublic(Builder $query)
    {
        $query->whereNotNull('published_at')
            ->where('private', false);
    }

    /**
     * Return if the album is public.
     */
    public function isPublic(): bool
    {
        return $this->isPublished() && $this->isPasswordLess();
    }

    /**
     * Return if the album is published.
     */
    public function isPublished(): bool
    {
        return $this->published_at !== null;
    }

    /**
     * Return if the album is password less.
     */
    public function isPasswordLess(): bool
    {
        return $this->private === false;
    }

    /**
     * Return the related user to this album.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return all the comments of this albums.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Return all the cosplayers of this album.
     */
    public function cosplayers(): BelongsToMany
    {
        return $this->belongsToMany(Cosplayer::class);
    }

    /**
     * Return all the categories of this album.
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    /**
     * Add media to Album::PICTURES_COLLECTION collection.
     *
     * @param  string|UploadedFile  $media
     */
    public function addPicture($media): Media
    {
        return $this->addMedia($media)
            ->usingFileName("{$this->slug}.{$media->getClientOriginalExtension()}")
            ->preservingOriginal()
            ->toMediaCollectionOnCloudDisk(self::PICTURES_COLLECTION);
    }

    /**
     * Register the collections of this album.
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection(self::PICTURES_COLLECTION)
            ->acceptsFile(static function (File $file) {
                return mb_strpos($file->mimeType, 'image/') === 0;
            });
    }

    /**
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion(self::RESPONSIVE_PICTURES_CONVERSION)
            ->optimize()
            ->withResponsiveImages()
            ->performOnCollections(self::PICTURES_COLLECTION);

        $this->addMediaConversion('thumb')
            ->width(400)
            ->optimize()
            ->performOnCollections(self::PICTURES_COLLECTION);
    }
}
