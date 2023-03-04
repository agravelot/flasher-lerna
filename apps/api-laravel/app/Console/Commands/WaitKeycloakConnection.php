<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class WaitKeycloakConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keycloak:wait';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Wait keycloak server';

    /**
     * Execute the console command.
     *
     * @throws RequestException
     */
    public function handle(): void
    {
        $this->info('Waiting keycloak : '.config('keycloak.url'));
        $response = Http::withOptions(['verify' => false])
            ->retry(50, 1000)
            ->timeout(2)
            ->get(config('keycloak.url').'/auth/');

        if (! $response->successful()) {
            $response->throw();
        }
    }
}
