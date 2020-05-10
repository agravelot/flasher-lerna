<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Support\Carbon;

class AdminUpdateTestimonialRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'published_at' => 'nullable|date',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('published_at') && $this->published_at !== null) {
            $this->merge(['published_at' => Carbon::parse($this->published_at)]);
        }
    }
}
