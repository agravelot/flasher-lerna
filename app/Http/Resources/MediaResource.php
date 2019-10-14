<?php

namespace App\Http\Resources;

use App\Models\Album;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

/** @mixin Media */
class MediaResource extends Resource
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
            'file_name' => $this->file_name,
            'thumb' => $this->getUrl('thumb'),
            'src_set' => $this->getSrcset(Album::RESPONSIVE_PICTURES_CONVERSION),
        ];
    }
}
