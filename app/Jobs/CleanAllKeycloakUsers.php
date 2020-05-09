<?php

namespace App\Jobs;

use App\Facades\Keycloak;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CleanAllKeycloakUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach (Keycloak::users()->all() as $user) {
            Keycloak::users()->delete($user->id);
        }
    }
}
