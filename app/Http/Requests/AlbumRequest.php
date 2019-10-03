<?php

namespace App\Http\Requests;

use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class AlbumRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $id = $this->route('album');

        return [
            'title' => ['required', 'string', 'min:2', 'max:255', Rule::unique('albums')->ignore($id)],
            'body' => 'nullable|max:65000',
            'published_at' => 'nullable|date', //2019-10-02T08:35:39.429Z
            'private' => 'sometimes|boolean',
            'categories' => 'nullable|array',
            'categories.*.id' => 'integer|min:1|exists:categories,id',
            'cosplayers' => 'nullable|array',
            'cosplayers.*.id' => 'integer|min:1|exists:cosplayers,id',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('published_at') && $this->published_at !== null) {
            $this->merge(['published_at' => Carbon::parse($this->published_at)]);
        }
    }
}
