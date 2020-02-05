<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

/** @mixin Category */
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
            'meta_description' => $this->meta_description,
            'description' => $this->description,
            'cover' => $this->whenLoaded('media', new MediaResource($this->cover)),
            'links' => [
                'related' => route('categories.show', ['category' => $this]),
            ],
        ];
    }
}
