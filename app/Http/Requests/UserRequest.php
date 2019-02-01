<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Requests;

class UserRequest extends Request
{
    public function rules()
    {
        $id = $this->route('user');

        return [
            'name' => 'required|string|min:2|max:255|unique:users,name,' . $id,
            'email' => 'required|string|email|min:2|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'cosplayer' => 'nullable|sometimes|integer|exists:cosplayers,id',
            'g-recaptcha-response' => auth()->check() && auth()->user()->isAdmin() ? 'nullable' : 'required|captcha',
        ];
    }
}
