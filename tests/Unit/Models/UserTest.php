<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ModelTestCase;

class UserTest extends ModelTestCase
{
    use WithFaker;

    public function testAdminPermission(): void
    {
        $user = factory(User::class)->state('admin')->make();

        $this->assertTrue($user->isAdmin(), 'User should have admin right');
    }

    public function testUserShouldNotHaveAdminPermission(): void
    {
        $user = factory(User::class)->state('user')->make();

        $this->assertFalse($user->isAdmin(), 'User should have basic user right');
    }
}
