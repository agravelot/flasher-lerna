<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Requests;

class SocialMediaRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('social_media');

        return [
            'name' => 'required|string|unique:social_media,name,' . $id,
            'icon' => 'required|string',
            'color' => 'required',
            'url' => 'required',
            'active' => 'required',
        ];
    }
}
