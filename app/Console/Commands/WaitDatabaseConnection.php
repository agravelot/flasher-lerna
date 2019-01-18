<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class WaitDatabaseConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:wait-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Wait until get database connection';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->waitConnection();
    }

    public function waitConnection()
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            sleep(1);
            $this->waitConnection();
        }
    }
}
