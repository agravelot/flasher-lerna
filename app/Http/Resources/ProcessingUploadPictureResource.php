<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Pion\Laravel\ChunkUpload\Save\AbstractSave;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin AbstractSave */
class ProcessingUploadPictureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     */
    public function toArray($request): array
    {
        return [
            'done' => $this->handler()->getPercentageDone(),
            'status' => true,
        ];
    }
}
