<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Contact\Http\Controllers;

use Exception;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Modules\Contact\Transformers\ContactResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
