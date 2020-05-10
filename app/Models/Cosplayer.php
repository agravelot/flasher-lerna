<?php

declare(strict_types=1);

namespace App\Models;

use App\Abilities\HasNameAsSlug;
use App\Abilities\HasSlugRouteKey;
use App\Adapters\Keycloak\UserRepresentation;
use App\Facades\Keycloak;
use App\Traits\ClearsResponseCache;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;

class Cosplayer extends Model implements HasMedia
{
    use Sluggable,
        SluggableScopeHelpers,
        InteractsWithMedia,
        HasSlugRouteKey,
        HasNameAsSlug,
        ClearsResponseCache,
        Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name', 'description', 'slug', 'user_id', 'sso_id'];

    /**
     * Return the initials of the cosplayer.
     */
    public function getInitialAttribute(): string
    {
        return mb_strtoupper(mb_substr($this->name, 0, 1));
    }

    public function getAvatarAttribute(): ?Media
    {
        return $this->getFirstMedia('avatar');
    }

    /**
     * Add media to Album::PICTURES_COLLECTION collection.
     *
     * @param  UploadedFile|null  $media
     */
    public function setAvatarAttribute($media): void
    {
        if (! $media) {
            optional($this->avatar)->delete();

            return;
        }

        $this->addMedia($media)
            ->usingFileName("{$this->slug}.{$media->clientExtension()}")
            ->preservingOriginal()
            ->withResponsiveImages()
            ->toMediaCollectionOnCloudDisk('avatar');
    }

    /**
     * Return the linked user.
     */
    public function user(): ?UserRepresentation
    {
        return $this->sso_id ? Keycloak::users()->find($this->sso_id) : null;
    }

    /**
     * Return the albums posted by this user.
     */
    public function albums(): BelongsToMany
    {
        return $this->belongsToMany(Album::class);
    }

    /**
     * Return the public albums posted by this user.
     */
    public function publicAlbums(): BelongsToMany
    {
        return $this->belongsToMany(PublicAlbum::class, 'album_cosplayer');
    }

    /**
     * Return the categories related to this user.
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    /**
     * Defining the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->acceptsFile(static function (File $file) {
                return Str::startsWith($file->mimeType, 'image/');
            })
            ->singleFile();
    }

    /**
     * Register the media conversions.
     *
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(?\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->crop('crop-center', 96, 96)
            ->performOnCollections('avatar');
    }

    public function searchableAs(): string
    {
        return 'cosplayers-'.config('app.env');
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
            'description' => $this->description,
            'thumb' => optional($this->avatar)->getUrl('thumb'),
            'url' => route('cosplayers.show', ['cosplayer' => $this]),
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return true;
    }
}
