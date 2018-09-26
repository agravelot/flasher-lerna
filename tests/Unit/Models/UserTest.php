<?php

namespace Tests\Unit;

use App\Models\Album;
use App\Models\Cosplayer;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ModelTestCase;

class UserTest extends ModelTestCase
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

    public function testBasicPermission()
    {
        $user = new User();
        $user->email = $this->faker->email;
        $user->role = "user";

        $this->assertFalse($user->isAdmin(), "user should have basic user right");
    }

    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions(new User(), [
            'name', 'email', 'password', 'role', 'slug'
        ], [
            'password', 'remember_token',
        ]);
    }
}
