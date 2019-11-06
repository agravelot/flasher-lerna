<?php

namespace App\Http\Controllers\Front;

use Illuminate\View\View;
use App\Models\Invitation;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;

class InvitationController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function show(string $token): View
    {
        /** @var Invitation $invitation */
        $invitation = Invitation::where('token', $token)->firstOrFail();

        $this->authorize('view', $invitation);

        $invitation->cosplayer->user()->associate(auth()->user());
        $invitation->cosplayer->save();
        $invitation->update(['confirmed_at' => now()]);

        return view('invitations.welcome', compact('invitation'));
    }
}
