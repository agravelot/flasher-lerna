<?php

namespace App\Http\Requests;

class ContactStoreRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|required|min:3|max:255',
            'email' => 'email',
            'message' => 'min:25',
            'g-recaptcha-response' => 'required|captcha'
        ];
    }
}
