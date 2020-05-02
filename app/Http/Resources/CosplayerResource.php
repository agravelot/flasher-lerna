<?php

namespace App\Http\Resources;

use App\Models\Cosplayer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;

/** @mixin Cosplayer */
class CosplayerResource extends JsonResource
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
            'avatar' => $this->whenLoaded('media', new MediaResource($this->avatar)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => $this->sso_id,
            'links' => [
                'related' => route('cosplayers.show', ['cosplayer' => $this]),
            ],
        ];
    }
}
