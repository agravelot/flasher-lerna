<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Unit\Http\Controllers\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cosplayer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Admin\AdminUserController;

class AdminUserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var AdminUserController
     */
    protected $controller;

    /**
     * @var User
     */
    protected $admin;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = factory(User::class)
            ->create([
                'role' => 'admin',
            ])->first();

        // We define the logged user to pass policies
        Auth::setUser($this->admin);
        $this->controller = new AdminUserController();
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function testIndexReturnsView()
    {
        $users = factory(User::class, 10)->create();

        $view = $this->controller->index();

        $this->assertSame('admin.users.index', $view->getName());
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function testCreateReturnsView()
    {
        $cosplayers = factory(Cosplayer::class, 10)->create();

        $view = $this->controller->create();

        $this->assertSame('admin.users.create', $view->getName());
    }

    public function testShowReturnsView()
    {
        $user = factory(User::class)->create();

        $view = $this->controller->show($user->id);

        $this->assertSame('admin.users.show', $view->getName());
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function testEditReturnsView()
    {
        $user = factory(User::class)->create();
        $cosplayers = factory(Cosplayer::class, 10)->create();

        $view = $this->controller->edit($user->id);

        $this->assertSame('admin.users.edit', $view->getName());
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function testDestroyRedirect()
    {
        $user = factory(User::class)->create();

        $response = $this->controller->destroy($user->id);

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    //TODO Test create and update
//    public function testStoreReturnsView()
//    {
//        $user = factory(User::class)->create()->first();
//        $userRequest = Mockery::mock(UserRequest::class);
//
//        $request = UserRequest::create('/admin/users/store', 'POST', [
//            'user' => $user->toArray()
//        ]);
//
//        // We define the logged user to pass policies
//        Auth::setUser($this->admin);
//
//        $userRequest->shouldReceive('all')
//            ->once()
//            ->with()
//            ->andReturn($request);
//
//        $this->userRepository->shouldReceive('create')
//            ->once()
//            ->with()
//            ->andReturn($user);
//
//        $view = $this->controller->store($request);
//
//        $this->assertEquals('admin.users.show', $view->getName());
//        $this->assertArraySubset(['user' => $user], $view->getData());
//    }
}
