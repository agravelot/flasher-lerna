<?php

namespace App\Jobs;

use App\Facades\Keycloak;
use App\Models\User;
use App\Services\Keycloak\Credential;
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
     * @param  Collection<User> $users
     */
    public function __construct(Collection $users)
    {
        $this->users = $users;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        foreach ($this->users as $user) {
            Keycloak::users()->create($user);
        }
    }
}
