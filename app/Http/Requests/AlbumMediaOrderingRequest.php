<?php

namespace App\Http\Requests;

use App\Models\Album;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class AlbumMediaOrderingRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $album = $this->route('album');

        return [
            'media_ids' => [
                'required',
                'array',
            ],
            'media_ids.*' => [
                'required',
                'integer',
                'exists:media,id',
                Rule::exists('media', 'id')
                    ->where(static function (Builder $query) use ($album) {
                        $query->where('model_id', $album->id)
                            ->where('model_type', Album::class);
                    }),
                ],
        ];
    }
}
