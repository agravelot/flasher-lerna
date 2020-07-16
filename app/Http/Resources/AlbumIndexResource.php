<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;

/** @mixin Album */
class AlbumIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'meta_description' => $this->meta_description,
            'title' => $this->title,
            'published_at' => $this->published_at,
            'private' => $this->private,
            //'media' => $this->whenLoaded('media', new MediaResource($this->getFirstMedia(Album::PICTURES_COLLECTION))),
            'media_count' => $this->media_count,
            'categories' => CategoryResource::collection($this->categories),
        ];
    }
}
