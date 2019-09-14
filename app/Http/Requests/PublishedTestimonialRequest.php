<?php

namespace App\Http\Requests;

class PublishedTestimonialRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'goldenbook_id' => 'required|exists:testimonials,id',
        ];
    }
}
