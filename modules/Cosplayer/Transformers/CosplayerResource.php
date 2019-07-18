<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Cosplayer\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Album\Transformers\MediaResource;

class CosplayerResource extends Resource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'avatar' => new MediaResource($this->avatar),
            'user_id' => optional($this->user)->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'links' => [
                'related' => route('cosplayers.show', ['cosplayers' => $this]),
            ],
        ];
    }
}
