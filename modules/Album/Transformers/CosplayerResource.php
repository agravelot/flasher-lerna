<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class CosplayerResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'thumb' => $this->getFirstMediaUrl('avatar', 'thumb'),
            'links' => [
                'related' => route('cosplayers.show', ['cosplayer' => $this]),
            ],
        ];
    }
}
