<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Requests;

class GoldenBookRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('goldenbook');

        return [
            'name' => 'string|required|min:2|max:255' . $id,
            'body' => 'string|required|min:42|max:65000',
            'email' => 'email|required|min:5|max:142',
            'g-recaptcha-response' => 'required|captcha',
        ];
    }
}
