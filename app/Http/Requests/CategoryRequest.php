<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CategoryRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'required', 'min:2', 'max:255', Rule::unique('categories')->ignore(optional($this->category)->id)],
            'description' => 'nullable|string|max:65555',
        ];
    }
}
