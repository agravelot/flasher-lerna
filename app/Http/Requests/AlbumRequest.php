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
            'slug' => 'nullable|string|max:255',
            'meta_description' => 'required|string|max:255',
            'body' => 'nullable|string',
            'published_at' => 'nullable|date', //2019-10-02T08:35:39.429Z
            'private' => 'required|boolean',
            'categories' => 'nullable|array',
            'categories.*.id' => 'integer|min:1|exists:categories,id',
            'cosplayers' => 'nullable|array',
            'cosplayers.*.id' => 'integer|min:1|exists:cosplayers,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->published_at !== null) {
            $this->merge(['published_at' => Carbon::parse($this->published_at)]);
        }

        if ($this->private === null) {
            $this->merge(['private' => true]);
        }
    }
}
