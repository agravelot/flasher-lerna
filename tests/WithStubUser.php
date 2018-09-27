<?php

namespace Tests;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait WithStubUser
{
    protected $user;

    public function createStubUser(array $data = [])
    {
        $data = array_merge([
            'name' => 'Test User',
            'email' => 'test-user-' . uniqid() . '@example.com',
            'password' => Hash::make('123456'),
        ], $data);

        return $this->user = User::create($data);
    }

    public function deleteStubUser()
    {
        $this->user->forceDelete();
    }
}
