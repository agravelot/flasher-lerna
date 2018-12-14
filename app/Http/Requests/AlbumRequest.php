<?php

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
            'title' => 'string|required|min:2|max:255|unique:albums,id,' . $id,
            'seo_title' => 'nullable',
            'body' => 'nullable|max:65000',
            'publish' => 'required|boolean',
            'password' => 'nullable|string|max:128',
            'categories' => 'array',
            'categories.*' => 'integer|min:1',
            'cosplayers' => 'array',
            'cosplayers.*' => 'integer|min:1',
            'pictures' => 'required|array',
            'pictures.*' => 'file|image|mimetypes:image/*|max:20000',

            //TODO Fix this
            Rule::exists('users')->where(function ($query) {
                $query->where('user_id', 1);
            }),
        ];

        return $rules;
    }
}
