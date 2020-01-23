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
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|min:5|max:255',
            'message' => 'required|min:42|max:65000',
            'g-recaptcha-response' => 'required|captcha',
        ];
    }
}
