<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testAdminPermission()
    {
        $user = new User();
        $user->email = $this->faker->email;
        $user->role = "admin";

        $this->assertTrue($user->isAdmin(), "user should have admin right");
    }

    public function testBasicPermission() {
        $user = new User();
        $user->email = $this->faker->email;
        $user->role = "user";

        $this->assertFalse($user->isAdmin(), "user should have basic user right");
    }
}
