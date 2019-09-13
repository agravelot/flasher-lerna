<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Transformers;

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
