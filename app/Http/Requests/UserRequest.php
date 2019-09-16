<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UserRequest extends Request
{
    public function rules(): array
    {
        $id = optional($this->route('user'))->id ?? $this->route('user');

        return [
            'name' => ['required', 'string', 'min:2', 'max:255', Rule::unique('users')->ignore($id)],
            'email' => ['required', 'string', 'email', 'min:2', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => ($id === null ? 'required|' : 'nullable|sometimes|').'string|min:6|confirmed',
            'role' => 'sometimes|required|string',
            'cosplayer' => 'nullable|sometimes|integer|exists:cosplayers,id',
            'g-recaptcha-response' => optional(auth()->user())->isAdmin() ? 'nullable' : 'required|captcha',
        ];
    }
}
