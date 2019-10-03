<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class MediaSettingResource extends Resource
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
            'value' => $this->value ? [
                'name' => optional($this->value)->name,
                'url' => optional($this->value)->getUrl(),
            ] : null,
            'type' => optional($this->type)->value,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
