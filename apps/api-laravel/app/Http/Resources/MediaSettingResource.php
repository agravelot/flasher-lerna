<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Setting */
class MediaSettingResource extends JsonResource
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
                'url' => optional($this->value)->getFullUrl(),
                'width' => optional($this->value)->getCustomProperty('width'),
                'height' => optional($this->value)->getCustomProperty('height'),
            ] : null,
            'type' => optional($this->type)->value,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
