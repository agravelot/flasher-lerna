<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CosplayerRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'min:2', 'max:255',
                Rule::unique('cosplayers')->ignore(optional($this->route('cosplayer'))->id),
            ],
            'description' => 'nullable|string|max:65000',
            'avatar' => 'sometimes|nullable|file|image|mimetypes:image/*|max:20000',
            'sso_id' => 'nullable|uuid',
        ];
    }
}
