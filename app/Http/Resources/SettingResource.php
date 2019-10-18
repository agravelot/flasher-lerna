<?php

namespace App\Http\Resources;

use App\Models\Setting;
use App\Enums\SettingType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

/** @mixin Setting */
class SettingResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
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
