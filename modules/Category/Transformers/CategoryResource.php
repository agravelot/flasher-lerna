<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Category\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Album\Transformers\MediaResource;

class CategoryResource extends Resource
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
            'name' => $this->name,
            'description' => $this->description,
            'cover' => $this->whenLoaded('media', new MediaResource($this->cover)),
            'links' => [
                'related' => route('categories.show', ['category' => $this]),
            ],
        ];
    }
}
