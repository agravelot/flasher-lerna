<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepository;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * Create a new controller instance.
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->middleware('guest');
        $this->repository = $repository;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param UserRequest $request
     * @return User|\Illuminate\Database\Eloquent\Model
     */
    protected function create(UserRequest $request)
    {
        return $this->repository->create($request->validated());
    }
}
