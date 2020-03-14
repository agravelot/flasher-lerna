<?php

namespace App\Http\Requests;

use App\Rules\FileExist;
use Illuminate\Validation\Rule;

class AlbumMediaAddedRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'album_id' => ['required', 'integer', Rule::exists('albums', 'id')],
            'media_name' => ['required', 'string', new FileExist('s3', "albums/$this->album_id")],
        ];
    }
}
