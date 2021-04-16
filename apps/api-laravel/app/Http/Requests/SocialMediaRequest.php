<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use LVR\Colour\Hex;

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
            'color' => ['required', new Hex],
            'url' => 'required',
            'active' => 'required',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->active === null) {
            $this->merge(['active' => false]);
        }
    }
}
