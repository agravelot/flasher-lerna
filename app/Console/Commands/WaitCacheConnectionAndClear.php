<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class WaitCacheConnectionAndClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-wait-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Wait until get cache connection and clear it';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->waitConnectionForClear();
    }

    public function waitConnectionForClear()
    {
        try {
            Cache::clear();
        } catch (Exception $exception) {
            sleep(1);
            $this->waitConnectionForClear();
        }
    }
}
