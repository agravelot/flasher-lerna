<?php

namespace App\Http\Controllers\Api\Admin\CosplayerInvitation;

use App\Models\Album;
use App\Models\Cosplayer;
use App\Mail\CosplayerInvitation;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ShareAlbumRequest;
use Illuminate\Auth\Access\AuthorizationException;

class AdminCosplayerInvitationController extends Controller
{
    /**
     * Share the album to the given contacts.
     *
     * @throws AuthorizationException
     */
    public function __invoke(Album $album, ShareAlbumRequest $request): JsonResponse
    {
        $this->authorize('update', $album);

        foreach ($request->get('contacts') as $contact) {
            $cosplayer = Cosplayer::findOrFail($contact['cosplayer_id']);
            $email = $contact['email'];

            Mail::to($email)->send(
                new CosplayerInvitation($cosplayer, $request->get('message'))
            );
        }

        return new JsonResponse(null, 201);
    }
}
