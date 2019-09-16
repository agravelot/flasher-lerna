<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class SocialMediaRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $id = $this->route('social_media');

        return [
            'name' => ['required', 'string', Rule::unique('social_media')->ignore($id)],
            'icon' => 'required|string',
            'color' => 'required',
            'url' => 'required',
            'active' => 'required',
        ];
    }
}
