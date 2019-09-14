<?php

namespace App\Http\Requests;

class StoreCoverCategoryRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'category_slug' => 'required|exists:categories,slug',
            'file' => 'required|file|max:20000',
        ];
    }
}
