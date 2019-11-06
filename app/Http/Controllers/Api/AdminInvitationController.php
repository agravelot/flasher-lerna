<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Invitation;
use Illuminate\Http\JsonResponse;
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

    public function index(): AnonymousResourceCollection
    {
        $invitations = Invitation::paginate();

        return InvitationResource::collection($invitations);
    }

    public function store(InvitationRequest $request): InvitationResource
    {
        $invitation = Invitation::create($request->validated());

        return new InvitationResource($invitation);
    }

    public function show(Invitation $invitation): InvitationResource
    {
        return new InvitationResource($invitation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws Exception
     */
    public function destroy(Invitation $invitation): JsonResponse
    {
        $invitation->delete();

        return new JsonResponse(null, 204);
    }
}
