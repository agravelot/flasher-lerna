<?php

namespace App\Http\Requests;

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
}
