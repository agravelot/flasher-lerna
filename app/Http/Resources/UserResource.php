<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     */
    public function toArray($request): array
    {
        /** @var User $user */
        $user = auth()->user();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'email_verified_at' => $this->email_verified_at,
            'actions' => [
                'impersonate' => $this->when($user && $user->canImpersonate(), route('impersonate', $this->id)),
            ],
        ];
    }
}
