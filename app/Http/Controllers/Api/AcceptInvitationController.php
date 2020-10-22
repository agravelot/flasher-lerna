<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvitationResource;
use App\Models\Invitation;
use Illuminate\Auth\Access\AuthorizationException;

class AcceptInvitationController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function __invoke(Invitation $invitation): InvitationResource
    {
        $this->authorize('view', $invitation);

        $invitation->cosplayer->sso_id = auth()->id() ?? auth()->user()->token->sub;
        $invitation->cosplayer->save();
        $invitation->update(['confirmed_at' => now()]);

        return new InvitationResource($invitation);
    }
}
