<?php

namespace App\Http\Requests;

class ContactStoreRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'string|required|min:3|max:255',
            'email' => 'email|min:5|max:142',
            'message' => 'required|min:42|max:65000',
            'g-recaptcha-response' => 'required|captcha',
        ];
    }
}
