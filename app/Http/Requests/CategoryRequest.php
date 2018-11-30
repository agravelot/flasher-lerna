<?php

namespace App\Http\Requests;

class CategoryRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('category');

        return [
            'name' => 'string|required|min:2|max:255|unique:categories,name,'.$id,
            'description' => 'nullable|string|max:65555',
        ];
    }
}
