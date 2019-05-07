<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Requests;

use App\Models\Category;

class CategoryRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = '';
        if ($this->method() === 'PATCH') {
            $id = Category::findBySlugOrFail($this->category)->id;
        }

        return [
            'name' => 'string|required|min:2|max:255|unique:categories,name,'.$id,
            'description' => 'nullable|string|max:65555',
        ];
    }
}
