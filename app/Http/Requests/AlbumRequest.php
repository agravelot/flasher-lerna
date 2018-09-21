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
        $pictures = $this->file('pictures');

        $rules = [
            'title' => 'string|required|min:2|max:255|unique:albums,id,' . $id,
            'seo_title' => 'nullable',
            'body' => 'nullable|max:65000',
            //TODO Fix true on edit
            'active' => 'boolean',
            'publish' => 'boolean',
            'password' => 'nullable|string|max:128',
            'pictures' => 'required',
            Rule::exists('users')->where(function ($query) {
                $query->where('user_id', 1);
            }),
        ];

        if ($pictures != null) {
            foreach ($pictures as $key => $picture) {
                $rules['pictures.' . $key] = 'image|mimes:jpeg,bmp,png|max:20000';
            }
        }

        return $rules;
    }
}
