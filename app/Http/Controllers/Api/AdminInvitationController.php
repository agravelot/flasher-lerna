<?php

namespace App\Http\Controllers\Api;

use App\Models\Album;
use App\Models\Invitation;
use App\Mail\InvitationMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\InvitationRequest;
use App\Http\Resources\InvitationResource;

class AdminInvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function store(Album $album, InvitationRequest $request): InvitationResource
    {
        $this->authorize('update', $album);

        $invitation = Invitation::create($request->validated());

        Mail::to($invitation->email)->send(new InvitationMail($invitation));

        return new InvitationResource($invitation);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invitation  $invitation
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Invitation $invitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Invitation  $invitation
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invitation $invitation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invitation  $invitation
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invitation $invitation)
    {
        //
    }
}
