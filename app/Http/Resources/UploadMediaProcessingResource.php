<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Pion\Laravel\ChunkUpload\Save\AbstractSave;

/** @mixin AbstractSave */
class UploadMediaProcessingResource extends JsonResource
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
