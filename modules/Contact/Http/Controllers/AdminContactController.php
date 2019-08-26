<?php

namespace Modules\Contact\Http\Controllers;

use App\Http\Requests\ContactStoreRequest;
use App\Models\Contact;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Modules\Contact\Transformers\ContactResource;
use Spatie\QueryBuilder\QueryBuilder;

class AdminContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
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
     *
     * @param  Contact  $contact
     *
     * @return ContactResource
     */
    public function show(Contact $contact): ContactResource
    {
        return new ContactResource($contact);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Contact  $contact
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Contact $contact): JsonResponse
    {
        $contact->delete();

        return response()->json(null, 204);
    }
}
