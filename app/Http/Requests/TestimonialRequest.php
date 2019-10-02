<?php

namespace App\Http\Requests;

class TestimonialRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $id = $this->route('goldenbook');

        return [
            'name' => 'string|required|min:2|max:255'.$id,
            'body' => 'string|required|min:42|max:65000',
            'email' => 'email|required|min:5|max:142',
            'g-recaptcha-response' => 'required|captcha',
        ];
    }
}
