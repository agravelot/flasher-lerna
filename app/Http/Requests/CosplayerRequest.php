<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Requests;

use App\Models\Cosplayer;

class CosplayerRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = '';
        if ($this->method() === 'PATCH') {
            $id = Cosplayer::findBySlugOrFail($this->cosplayer)->id;
        }

        return [
            'name' => 'required|string|min:2|max:255|unique:cosplayers,name,' . $id,
            'description' => 'nullable|string|max:65000',
            'avatar' => 'nullable|file|image|mimetypes:image/*|max:20000',
            'user_id' => 'nullable|integer|min:1|exists:users,id',
        ];
    }
}
