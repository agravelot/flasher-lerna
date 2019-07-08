<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdateSettingRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // TODO Use getOriginal('nullable')
        // TODO check file and types
        $nullable = true;

        return [
            'value' => [Rule::requiredIf(! $nullable)],
            'file' => 'sometimes|file|max:20000',
        ];
    }
}
