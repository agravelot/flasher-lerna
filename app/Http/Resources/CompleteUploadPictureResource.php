<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompleteUploadPictureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     */
    public function toArray($request): array
    {
        return [
            'path' => $this->getUrl(),
            'name' => $this->file_name,
            'mime_type' => $this->mime_type,
        ];
    }
}
