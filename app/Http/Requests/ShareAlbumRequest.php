<?php

namespace App\Http\Requests;

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
            'contacts.*.email' => 'required|email',
            'contacts.*.cosplayer_id' => 'required|int|exists:cosplayers,id',
            'message' => 'required|string',
        ];
    }
}
