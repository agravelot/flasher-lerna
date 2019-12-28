<?php

namespace Tests\Unit;

use App\Models\Album;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ModelTestCase;

class UserTest extends ModelTestCase
{
    use WithFaker;

    public function testAdminPermission()
    {
        $user = factory(User::class)->state('admin')->make();

        $this->assertTrue($user->isAdmin(), 'user should have admin right');
    }

    public function testBasicPermission()
    {
        $user = factory(User::class)->state('user')->make();

        $this->assertFalse($user->isAdmin(), 'user should have basic user right');
    }

    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions(new User(), [
            'name', 'email', 'password', 'role', 'notify_on_album_published',
        ], [
            'password', 'remember_token',
        ], ['*'], [], ['id' => 'int'], [
            'email_verified_at', 'updated_at', 'created_at',
        ]);
    }

    public function testHasManyAlbumsRelationship()
    {
        $user = factory(User::class)->make();

        $relation = $user->albums();

        $this->assertHasManyRelation($relation, $user, new Album(), 'user_id');
    }

    public function testHasManyPostsRelationship()
    {
        $user = factory(User::class)->make();

        $relation = $user->posts();

        $this->assertHasManyRelation($relation, $user, new Post(), 'user_id');
    }
}
