<?php

namespace App\Http\Requests;

class StorePictureAlbumRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'album_slug' => 'required|exists:albums,slug',
            'file' => 'required|file|max:50000',
        ];
    }
}
