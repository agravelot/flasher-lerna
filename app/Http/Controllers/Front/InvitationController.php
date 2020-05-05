<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\View\View;

class InvitationController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function show(Invitation $invitation): View
    {
        $this->authorize('view', $invitation);

        $invitation->cosplayer->sso_id = auth()->id();
        $invitation->cosplayer->save();
        $invitation->update(['confirmed_at' => now()]);

        return view('invitations.welcome', compact('invitation'));
    }
}
