<?php

namespace App\Jobs;

use App\Adapters\Keycloak\UserRepresentation;
use App\Facades\Keycloak;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class AddKeycloakUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Collection $users;

    /**
     * Create a new job instance.
     *
     * @param  Collection<UserRepresentation> $users
     */
    public function __construct(Collection $users)
    {
        $this->users = $users;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->users as $user) {
            Keycloak::users()->create($user);
        }
    }
}
