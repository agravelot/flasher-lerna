<?php

namespace App\Http\Resources;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

/** @mixin Page */
class PageResource extends Resource
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
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
