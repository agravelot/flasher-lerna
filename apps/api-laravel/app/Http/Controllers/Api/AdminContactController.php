<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Spatie\QueryBuilder\QueryBuilder;

class AdminContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return ContactResource::collection(
            QueryBuilder::for(Contact::class)
                ->allowedFilters('name')->paginate()
        );
    }

    /**
     * Show the specified resource.
     */
    public function show(Contact $contact): ContactResource
    {
        return new ContactResource($contact);
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @throws Exception
     */
    public function destroy(Contact $contact): JsonResponse
    {
        $contact->delete();

        return response()->json(null, 204);
    }
}
