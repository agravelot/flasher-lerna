<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\SettingType;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;

/** @mixin Setting */
class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     */
    public function toArray($request): array
    {
        if ($this->type->value === SettingType::Media) {
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
