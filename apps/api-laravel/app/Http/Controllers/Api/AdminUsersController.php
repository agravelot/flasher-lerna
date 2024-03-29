<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Adapters\Keycloak\UserQuery;
use App\Facades\Keycloak;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = new UserQuery();
        $query->search = $request->get('search');
        $query->max = 10;
        $users = Keycloak::users()->all($query);

        return new JsonResponse(['data' => $users]);
    }

    public function show(string $user): JsonResponse
    {
        return new JsonResponse(Keycloak::users()->find($user));
    }
}
