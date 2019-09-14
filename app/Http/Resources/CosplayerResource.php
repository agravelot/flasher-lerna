<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class CosplayerResource extends Resource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'avatar' => new MediaResource($this->avatar),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => $this->whenLoaded('user', new UserResource($this->user)),
            'links' => [
                'related' => route('cosplayers.show', ['cosplayers' => $this]),
            ],
        ];
    }
}
