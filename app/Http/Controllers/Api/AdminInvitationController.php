<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvitationRequest;
use App\Http\Resources\InvitationResource;
use App\Models\Invitation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminInvitationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Invitation::class);
    }

    public function index(): AnonymousResourceCollection
    {
        $invitations = Invitation::with('cosplayer.media')->paginate();

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
