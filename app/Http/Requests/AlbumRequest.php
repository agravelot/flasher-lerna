<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
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
            'title' => 'string|required|min:2|max:255|unique:albums,id,' . $id,
            'seo_title' => 'nullable',
            'body' => 'nullable|max:65000',
            'published_at' => 'required|boolean',
            'password' => 'nullable|string|max:128',
            'categories' => 'array',
            'categories.*' => 'integer|min:1',
            'cosplayers' => 'array',
            'cosplayers.*' => 'integer|min:1',
        ];

        if ($this->method() === 'POST') {
            array_merge($rules, [
                'pictures' => 'required|array',
                'pictures.*' => 'file|image|mimetypes:image/*|max:20000',
            ]);
        }

        return $rules;
    }
}
