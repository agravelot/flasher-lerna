<?php

namespace App\Http\Requests;

class CosplayerRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('cosplayer');

        return [
            'name' => 'string|required|min:2|max:255|unique:cosplayers,id,' . $id,
        ];
    }
}
