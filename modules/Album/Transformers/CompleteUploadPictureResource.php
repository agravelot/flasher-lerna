<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\Models\Media;

class CompleteUploadPictureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
//        $media = Media::newModelInstance($this);
        //TODO Remove data
        $media = $this;
        return [
            'path' => $media->getUrl(),
            'name' => $media->file_name,
            'mime_type' => $media->mime_type,
        ];
    }
}
