<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Requests;

use App\Models\Album;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;

class DeletePictureAlbumRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'media_id' => [
                'required', 'integer',
                Rule::exists('media', 'id')->where(function (Builder $query) {
                    $query->where('model_type', Album::class)
                        ->where('model_id', $this->album->id);
                }),
            ],
        ];
    }
}
