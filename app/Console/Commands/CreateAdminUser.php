<?php

namespace App\Console\Commands;

use App\Http\Requests\UserRequest;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Auth\Events\Verified;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create 
                            {role? : Role} 
                            {name? : Username} 
                            {email? : Email-address} 
                            {password? : Password}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user';
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * Create a new command instance.
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $role = $this->argument('role');
        $username = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        if ($role === null) {
            $role = $this->choice('Please select a user role', ['admin', 'user'], 'user');
        }

        if ($username === null) {
            $username = $this->ask('Please enter a username');
        }


        if ($email === null) {
            $email = $this->ask('Enter your user email');
        }


        if ($password === null) {
            $password = $this->secret('Enter your user password');
        }

        $data = [
            'name' => $username,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ];

        // Only get filtered keys for name and email
        $filter = array_fill_keys(['name', 'email'], '');
        $rules = array_intersect_key((new UserRequest())->rules(), $filter);

        $this->validate($data, $rules);
        $this->createUser($data);

        $this->info('User created successfully');

        return 0;
    }

    private function validate(array $data, array $rules)
    {
        $validator = Validator::make($data, $rules);
        $validator->errors()->all();

        if ($validator->fails()) {
            $this->error('Admin User not created. See error messages below:');

            foreach ($validator->errors()->all() as $error) {
                $this->warn($error);
            }

            return 1;
        }
    }

    private function createUser($data)
    {
        $user = $this->repository->create($data);
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
    }
}
