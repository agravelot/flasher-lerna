<?php

namespace App\Http\Requests;


class UserRequest extends Request
{
    /**
     * @return array
     * @throws \Exception
     */
    public function rules()
    {
        $id = $this->route('user');
//        dd($this->method());

        $RULE_CREATE = [
            'name' => 'required|string|min:2|max:255|unique:users,name,' . $id,
            'email' => 'required|string|email|min:2|max:255|unique:users,email,' . $id,
            'password' => 'required|string|min:6|confirmed',
        ];
        $RULE_UPDATE = [
            'name' => 'required|string|min:2|max:255|unique:users,name,' . $id,
            'email' => 'required|string|email|min:2|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
        ];

        switch ($this->method()) {
            case 'POST':
                return $RULE_CREATE;
            case 'PUT':
                return $RULE_CREATE;
            case 'PATCH':
                return $RULE_UPDATE;
        }
        throw new \Exception('Missing rule');
    }
}
