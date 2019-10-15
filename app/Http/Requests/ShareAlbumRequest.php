<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShareAlbumRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'contacts' => 'required|array',
            'contacts.*.name' => 'required|string',
            'contacts.*.email' => 'required|email',
            'message' => 'required|string'
        ];
    }
}
