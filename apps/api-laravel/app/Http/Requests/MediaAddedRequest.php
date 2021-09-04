<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class MediaAddedRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'Upload' => ['required', 'array'],
            'Upload.ID' => ['nullable', 'string'], // Will be empty during the pre-create event
            'Upload.Size' => ['required', 'integer'],
            'Upload.Offset' => ['required', 'integer'],
            'Upload.IsFinal' => ['required', 'boolean'],
            'Upload.IsPartial' => ['required', 'boolean'],
            'Upload.PartialUploads' => [],
            'Upload.MetaData' => ['required', 'array'],
            'Upload.MetaData.filename' => ['required', 'string'],
            'Upload.MetaData.modelClass' => ['required', Rule::in(['album'])],
            'Upload.MetaData.modelId' => ['required', 'integer', Rule::exists('albums', 'id')],
            'Upload.Storage' => ['nullable', 'array'],
            'Upload.Storage.Type' => ['nullable', 'string'],
            'Upload.Storage.Path' => ['nullable', 'string'],
            'HTTPRequest' => ['required', 'array'],
            'HTTPRequest.Method' => ['required', 'string'],
            'HTTPRequest.URI' => ['required', 'string'],
            'HTTPRequest.RemoteAddr' => ['required', 'string'],
            'HTTPRequest.Header' => ['required', 'array'],
        ];
    }
}
