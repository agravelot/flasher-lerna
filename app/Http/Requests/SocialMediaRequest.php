<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class SocialMediaRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $id = $this->route('social_media');

        return [
            'name' => ['required', 'string', Rule::unique('social_media')->ignore($id)],
            'icon' => 'required|string',
            'color' => 'required',
            'url' => 'required',
            'active' => 'required',
        ];
    }
}
