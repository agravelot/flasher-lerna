<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class AlbumShowResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'published_at' => $this->published_at,
            'body' => $this->body,
            'private' => $this->private,
            'medias' => MediaResource::collection($this->media),
            'categories' => $this->categories,
            'cosplayers' => $this->cosplayer,
        ];
    }
}
