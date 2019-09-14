<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CosplayerRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'min:2', 'max:255',
                Rule::unique('cosplayers')->ignore(optional($this->route('cosplayer'))->id),
            ],
            'description' => 'nullable|string|max:65000',
            'avatar' => 'sometimes|nullable|file|image|mimetypes:image/*|max:20000',
            'user_id' => 'nullable|integer|min:1|exists:users,id',
        ];
    }
}
