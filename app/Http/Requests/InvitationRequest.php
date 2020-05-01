<?php

namespace App\Http\Requests;

class InvitationRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'cosplayer_id' => 'required|int|min:0|exists:cosplayers,id',
            'message' => 'string|nullable',
        ];
    }
}
