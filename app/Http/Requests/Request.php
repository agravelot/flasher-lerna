<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    /**
     * Authorization.
     */
    public function authorize(): bool
    {
        return true;
    }
}
