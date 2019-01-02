<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Http\Requests;

class CosplayerRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('cosplayer');

        return [
            'name' => 'string|required|min:2|max:255|unique:cosplayers,name,' . $id,
            'description' => 'nullable|max:65000',
            'avatar' => 'nullable|file|image|mimetypes:image/*|max:2000000',
        ];
    }
}
