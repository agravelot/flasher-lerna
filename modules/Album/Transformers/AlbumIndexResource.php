<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Transformers;

use Illuminate\Http\Request;
use Modules\Album\Entities\Album;
use Illuminate\Http\Resources\Json\Resource;

class AlbumIndexResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'published_at' => $this->published_at,
            'private' => $this->private,
            //'media' => $this->whenLoaded('media', new MediaResource($this->getFirstMedia(Album::PICTURES_COLLECTION))),
            'media_count' => $this->media_count,
        ];
    }
}
