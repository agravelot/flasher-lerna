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
     * @var UserRepository
     */
    private $repository;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:admin:create 
                            {name : Username} 
                            {email : Email-address} 
                            {password : Password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an administrator user';

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
        $username = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        // Only get filtered keys
        $filter = array_fill_keys(['name', 'email'], '');
        $rules = array_intersect_key((new UserRequest())->rules(), $filter);

        $data = [
            'name' => $username,
            'email' => $email,
            'password' => $password,
            'role' => 'admin'
        ];

        // Validate
        $validator = Validator::make($data, $rules);
        $validator->errors()->all();

        if ($validator->fails()) {
            $this->error('Admin User not created. See error messages below:');

            foreach ($validator->errors()->all() as $error) {
                $this->warn($error);
            }
            return 1;
        }

        // Create user
        $user = $this->repository->create($data);
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        $this->info('User created successfully');
        return 0;
    }
}
