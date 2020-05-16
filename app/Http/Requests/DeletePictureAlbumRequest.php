<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Album;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

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
                Rule::exists('media', 'id')->where(function (Builder $query): void {
                    $query->where('model_type', Album::class)
                        ->where('model_id', $this->album->id);
                }),
            ],
        ];
    }
}
