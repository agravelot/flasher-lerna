<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Transformers;

use Illuminate\Http\Request;
use Modules\Core\Enums\SettingType;
use Illuminate\Http\Resources\Json\Resource;

class SettingResource extends Resource
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
        $isMediaSetting = $this->type->value === SettingType::Media;

        if ($isMediaSetting) {
            return (new MediaSettingResource($this))->toArray($request);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'value' => $this->value,
            'type' => $this->type->value,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
