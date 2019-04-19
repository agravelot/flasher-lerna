<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

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
            'title' => ['required', 'string', 'min:2', 'max:255', Rule::unique('albums')->ignore($id)],
            'body' => 'nullable|max:65000',
            'published_at' => 'nullable|date', //2015-06-10 01:10:25
            'private' => 'required|boolean',
            'categories' => 'nullable|array',
            'categories.*.id' => 'integer|min:1|exists:categories,id',
            'cosplayers' => 'nullable|array',
            'cosplayers.*.id' => 'integer|min:1|exists:cosplayers,id',
        ];

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->has('published_at') && $this->published_at !== null) {
            $this->merge(['published_at' => new \DateTime($this->published_at)]);
        }
    }
}
