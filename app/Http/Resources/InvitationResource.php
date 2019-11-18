<?php

namespace App\Http\Resources;

use App\Models\Invitation;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Invitation */
class InvitationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'cosplayer' => new CosplayerResource($this->cosplayer),
            'message' => $this->message,
            'confirmed_at' => $this->confirmed_at,
            'expired' => $this->isExpired(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
