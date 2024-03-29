<?php

declare(strict_types=1);

namespace App\Models;

use App\Abilities\HasSlugRouteKey;
use App\Abilities\HasTitleAsSlug;
use App\Adapters\Keycloak\UserRepresentation;
use App\Facades\Keycloak;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Album extends Model implements HasMedia
{
    use Sluggable,
        SluggableScopeHelpers,
        InteractsWithMedia,
        HasSlugRouteKey,
        HasTitleAsSlug,
        Searchable;

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
        'meta_description',
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

    public function getCoverResponsiveAttribute(): ?HtmlableMedia
    {
        return optional($this->cover, static function (Media $media) {
            return $media(self::RESPONSIVE_PICTURES_CONVERSION);
        });
    }

    /**
     * Scope for public albums.
     */
    public function scopePublic(Builder $query): void
    {
        $query->whereNotNull('published_at')->where('private', false);
    }

    /**
     * Scope for public albums.
     */
    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at');
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
    public function user(): UserRepresentation
    {
        return Keycloak::users()->find($this->sso_id);
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
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Add media to Album::PICTURES_COLLECTION collection.
     *
     * @param  string|UploadedFile  $media
     */
    public function addPicture($media): Media
    {
        [$width, $height] = getimagesize($media->getRealPath());

        return $this->addMedia($media)
            ->usingFileName("{$this->slug}.{$media->getClientOriginalExtension()}")
            ->preservingOriginal()
            ->withCustomProperties(['width' => $width, 'height' => $height])
            ->toMediaCollectionOnCloudDisk(self::PICTURES_COLLECTION);
    }

    /**
     * Register the collections of this album.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::PICTURES_COLLECTION)
            ->acceptsFile(static function (File $file) {
                return Str::startsWith($file->mimeType, 'image/');
            });
    }

    public function searchableAs(): string
    {
        return 'albums-'.config('app.env');
    }

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'meta_description' => $this->meta_description,
            'cover' => optional($this->cover)->getUrl(),
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->isPublic();
    }
}
