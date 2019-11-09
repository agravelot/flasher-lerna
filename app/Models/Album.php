<?php

namespace App\Models;

use Spatie\Feed\Feedable;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\File;
use App\Abilities\AlbumFeedable;
use App\Abilities\HasTitleAsSlug;
use App\Abilities\HasSlugRouteKey;
use Illuminate\Support\HtmlString;
use App\Traits\ClearsResponseCache;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Image\Exceptions\InvalidManipulation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Album extends Model implements HasMedia, Feedable
{
    use Sluggable,
        SluggableScopeHelpers,
        HasMediaTrait,
        HasSlugRouteKey,
        HasTitleAsSlug,
        ClearsResponseCache,
        AlbumFeedable;

    public const PICTURES_COLLECTION = 'pictures';
    public const RESPONSIVE_PICTURES_CONVERSION = 'responsive';

    /**
     * @var array<string>
     */
    protected $with = ['media'];

    /**
     * @var array<string>
     */
    protected $dates = [
        'published_at', 'updated_at', 'created_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'private' => 'bool',
    ];

    protected $attributes = [
        'notify_users_on_published' => true,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'body',
        'published_at',
        'user_id',
        'private',
        'notify_users_on_published',
    ];

    public function getZipFileNameAttribute(): string
    {
        return $this->title.'.zip';
    }

    public function getCoverAttribute(): ?Media
    {
        return $this->getFirstMedia(self::PICTURES_COLLECTION);
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
        $query->whereNotNull('published_at')->where('private', false);
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
                return Str::startsWith($file->mimeType, 'image/');
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
