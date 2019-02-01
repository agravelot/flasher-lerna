<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Requests;

class AlbumRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('album');

        $rules = [
            'title' => 'required|string|min:2|max:255|unique:albums,id,' . $id,
            'body' => 'nullable|max:65000',
            'published_at' => 'nullable|date',
            'private' => 'required|boolean',
            'categories' => 'array',
            'categories.*' => 'integer|min:1|exists:categories,id',
            'cosplayers' => 'array',
            'cosplayers.*' => 'integer|min:1|exists:cosplayers,id',
            'pictures.*' => 'sometimes|file|image|mimetypes:image/*|max:20000',
        ];

        return $rules;
    }
}
