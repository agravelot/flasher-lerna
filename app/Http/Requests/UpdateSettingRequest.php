<?php

namespace App\Http\Requests;

use App\Models\Setting;
use App\Enums\SettingType;

class UpdateSettingRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [];

        /** @var Setting $setting */
        $setting = $this->setting;

        // TODO Use getOriginal('nullable')
        // TODO check file and types
        $nullable = true;

        if (! $nullable) {
            $rules['value'] = 'required';
        }

        $type = $setting->type->value;
        if ($type === SettingType::Email) {
            $rules['value'] = 'email';
        } elseif ($type === SettingType::Media) {
            $rules['file'] = 'sometimes|file|max:20000';
        }

        return $rules;
    }
}
