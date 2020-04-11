<?php

namespace App\Http\Resources;

use App\Models\Media;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Media */
class UploadMediaCompletedResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'path' => $this->getUrl(),
            'name' => $this->file_name,
            'mime_type' => $this->mime_type,
        ];
    }
}
