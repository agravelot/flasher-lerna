<?php

namespace App\Http\Controllers\Front;

use Illuminate\View\View;
use App\Models\Invitation;
use App\Http\Controllers\Controller;

class InvitationController extends Controller
{
    public function show(string $token): View
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();
        $invitation->cosplayer->user()->associate(auth()->user());
        $invitation->cosplayer->save();
        $invitation->update(['confirmed_at' => now()]);

        return view('invitations.show', compact('invitation'));
    }
}
