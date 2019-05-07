<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Http\Requests;

use App\Http\Requests\Request;

class StorePictureAlbumRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'album_slug' => 'required|exists:albums,slug',
            'file' => 'required|file|max:20000|mimetypes:image/*,application/octet-stream,application/x-dosexec',
        ];
    }
}
