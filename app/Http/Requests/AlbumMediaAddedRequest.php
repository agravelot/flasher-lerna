<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class AlbumMediaAddedRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'album_slug' => ['required', Rule::exists('albums', 'slug')],
            'media_url' => 'required|string|url',
        ];
    }
}
