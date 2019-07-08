<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class MediaSettingResource extends Resource
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
            'name' => $this->name,
            'value' => $this->value ? [
                'name' => optional($this->value)->name,
                'url' => optional($this->value)->getUrl(),
            ] : null,
            'type' => $this->type->value,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
