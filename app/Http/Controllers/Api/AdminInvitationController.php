<?php

namespace App\Http\Controllers\Api;

use App\Models\Album;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\InvitationRequest;
use App\Http\Resources\InvitationResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminInvitationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Invitation::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $invitations = Invitation::all();

        return InvitationResource::collection($invitations);
    }

    public function store(Album $album, InvitationRequest $request): InvitationResource
    {
        $invitation = Invitation::create($request->validated());

        return new InvitationResource($invitation);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invitation  $invitation
     *
     * @return Response
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
     * @return Response
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
     * @return Response
     */
    public function destroy(Invitation $invitation)
    {
        //
    }
}
