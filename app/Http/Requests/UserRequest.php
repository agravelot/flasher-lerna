<?php

namespace App\Http\Requests;


class UserRequest extends Request
{
    public function rules()
    {
        $id = $this->route('user');

        $RULE_UPDATE = [
            'name' => 'required|string|min:2|max:255|unique:users,name,' . $id,
            'email' => 'required|string|email|min:2|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'cosplayer' => 'nullable|sometimes|integer|exists:cosplayers,id',
        ];

        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            $captcha = ['g-recaptcha-response' => 'required|captcha'];
            return array_merge($RULE_UPDATE, $captcha);
        }
        return $RULE_UPDATE;
    }
}
