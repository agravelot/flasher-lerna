<?php

declare(strict_types=1);

namespace App\Http\Requests;

class ContactStoreApiRequest extends Request
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
        ];
    }
}
