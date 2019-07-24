<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Models;

use Eloquent;
use Spatie\MediaLibrary\File;
use Illuminate\Support\Carbon;
use App\Abilities\HasTitleAsSlug;
use App\Abilities\HasSlugRouteKey;
use Illuminate\Support\HtmlString;
use Spatie\MediaLibrary\Models\Media;
use App\Models\Contracts\OpenGraphable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Traits\ClearsResponseCache;
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album public()
 */
class Album extends Model implements HasMedia, OpenGraphable, ArticleOpenGraphable, ImagesOpenGraphable
{
    use Sluggable, SluggableScopeHelpers, HasMediaTrait, HasSlugRouteKey, HasTitleAsSlug, ClearsResponseCache;

    public const PICTURES_COLLECTION = 'pictures';
    public const RESPONSIVE_PICTURES_CONVERSION = 'responsive';

//    protected $with = ['media'];

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

    public function getCoverAttribute(): ?Media
    {
        return $this->getFirstMedia(self::PICTURES_COLLECTION);
    }

    public function getCoverResponsiveAttribute(): ?HtmlString
    {
        return optional($this->getFirstMedia(self::PICTURES_COLLECTION), function (Media $media) {
            return $media(self::RESPONSIVE_PICTURES_CONVERSION);
        });
    }

    /**
     * Scope for public albums.
     *
     * @param  Builder  $query
     */
    public function scopePublic(Builder $query)
    {
        $query->whereNotNull('published_at')
            ->where('private', false);
    }

    /**
     * Return if the album is public.
     *
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->isPublished() && $this->isPasswordLess();
    }

    /**
     * Return if the album is published.
     *
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->published_at !== null;
    }

    /**
     * Return if the album is password less.
     *
     * @return bool
     */
    public function isPasswordLess(): bool
    {
        return $this->private === false;
    }

    /**
     * Return the related user to this album.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return all the comments of this albums.
     *
     * @return MorphMany
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Return all the cosplayers of this album.
     *
     * @return BelongsToMany
     */
    public function cosplayers(): BelongsToMany
    {
        return $this->belongsToMany(Cosplayer::class);
    }

    /**
     * Return all the categories of this album.
     *
     * @return MorphToMany
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    /**
     * Add media to Album::PICTURES_COLLECTION collection.
     *
     * @param  string|UploadedFile  $media
     *
     * @return Media
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
     * @param  Media|null  $media
     *
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
