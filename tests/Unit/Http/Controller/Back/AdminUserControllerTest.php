<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\Admin\AdminUserController;
use App\Models\User;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class AdminUserControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $controller;
    protected $admin;
    protected $userRepository;

    public function setUp()
    {
        $this->afterApplicationCreated(function () {
            $this->userRepository = Mockery::mock(UserRepository::class);
        });

        parent::setUp();

        $this->controller = new AdminUserController($this->userRepository);

        $this->admin = factory(User::class)
            ->create([
                'role' => 'admin',
            ])->first();
    }

    public function testIndexReturnsView()
    {
        $users = factory(User::class, 10)->create();

        // We define the logged user to pass policies
        Auth::setUser($this->admin);

        $this->userRepository->shouldReceive('paginate')
            ->once()
            ->with(10)
            ->andReturn($users);

        $view = $this->controller->index();

        $this->assertEquals('admin.users.index', $view->getName());
        $this->assertArraySubset(['users' => $users], $view->getData());
    }

    public function testCreateReturnsView()
    {
        // We define the logged user to pass policies
        Auth::setUser($this->admin);

        $view = $this->controller->create();

        $this->assertEquals('admin.users.create', $view->getName());
    }

    public function testShowReturnsView()
    {
        $user = factory(User::class)->create();

        // We define the logged user to pass policies
        Auth::setUser($this->admin);

        $this->userRepository->shouldReceive('find')
            ->once()
            ->with($user->id)
            ->andReturn($user);

        $view = $this->controller->show($user->id);

        $this->assertEquals('admin.users.show', $view->getName());
        $this->assertArraySubset(['user' => $user], $view->getData());
    }

    public function testEditReturnsView()
    {
        $user = factory(User::class)->create();

        // We define the logged user to pass policies
        Auth::setUser($this->admin);

        $this->userRepository->shouldReceive('find')
            ->once()
            ->with($user->id)
            ->andReturn($user);

        $view = $this->controller->edit($user->id);

        $this->assertEquals('admin.users.edit', $view->getName());
        $this->assertArraySubset(['user' => $user], $view->getData());
    }

    public function testDestroyRedirect()
    {
        $user = factory(User::class)->create();

        // We define the logged user to pass policies
        Auth::setUser($this->admin);

        $this->userRepository->shouldReceive('delete')
            ->once()
            ->with($user->id)
            ->andReturn($user);

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
