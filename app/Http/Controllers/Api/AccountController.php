<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(User $user)
    {
        auth('web')->logout();
        $user->delete();

        return new JsonResponse();
    }
}
