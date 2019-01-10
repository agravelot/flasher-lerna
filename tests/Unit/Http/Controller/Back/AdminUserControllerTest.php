<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\Admin\AdminUserController;
use App\Models\Cosplayer;
use App\Models\User;
use App\Repositories\Contracts\CosplayerRepository;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

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

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var CosplayerRepository
     */
    protected $cosplayerRepository;

    public function setUp()
    {
        $this->afterApplicationCreated(function () {
            $this->userRepository = Mockery::mock(UserRepository::class);
            $this->cosplayerRepository = Mockery::mock(CosplayerRepository::class);
        });

        parent::setUp();

        $this->controller = new AdminUserController($this->userRepository, $this->cosplayerRepository);

        $this->admin = factory(User::class)
            ->create([
                'role' => 'admin',
            ])->first();

        // We define the logged user to pass policies
        Auth::setUser($this->admin);
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function testIndexReturnsView()
    {
        $users = factory(User::class, 10)->create();

        $this->cosplayerRepository->shouldReceive('paginate')->with(10)->andReturn($users);
        $this->userRepository->shouldReceive('with')->with('cosplayer')->andReturn(Mockery::self())
            ->getMock()->shouldReceive('paginate')->with(10)->andReturn($users);

        $view = $this->controller->index();

        $this->assertSame('admin.users.index', $view->getName());
        $this->assertArraySubset(['users' => $users], $view->getData());
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function testCreateReturnsView()
    {
        $cosplayers = factory(Cosplayer::class, 10)->create();

        $this->cosplayerRepository->shouldReceive('with')->with('user')->andReturn(Mockery::self())
            ->getMock()->shouldReceive('all')->with(['id', 'name'])->andReturn($cosplayers);

        $view = $this->controller->create();

        $this->assertSame('admin.users.create', $view->getName());
    }

    public function testShowReturnsView()
    {
        $user = factory(User::class)->create();

        $this->userRepository->shouldReceive('find')
            ->once()
            ->with($user->id)
            ->andReturn($user);

        $view = $this->controller->show($user->id);

        $this->assertSame('admin.users.show', $view->getName());
        $this->assertArraySubset(['user' => $user], $view->getData());
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function testEditReturnsView()
    {
        $user = factory(User::class)->create();
        $cosplayers = factory(Cosplayer::class, 10)->create();

        $this->userRepository->shouldReceive('find')
            ->once()
            ->with($user->id)
            ->andReturn($user);

        $this->cosplayerRepository->shouldReceive('with')->with('user')->once()->andReturn(Mockery::self())
            ->getMock()->shouldReceive('all')->with(['id', 'name'])->once()->andReturn($cosplayers);

        $view = $this->controller->edit($user->id);

        $this->assertSame('admin.users.edit', $view->getName());
        $this->assertArraySubset(['user' => $user], $view->getData());
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function testDestroyRedirect()
    {
        $user = factory(User::class)->create();

        $this->userRepository->shouldReceive('find')
            ->once()
            ->with($user->id)
            ->andReturn($user);

        $this->userRepository->shouldReceive('delete')
            ->once()
            ->with($user->id);

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
