<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\MediaAddedWebhook;
use Illuminate\Http\Response;

class AdminMediaWebhook extends Controller
{
    public function store(): Response
    {
        MediaAddedWebhook::dispatch();

        return response()->noContent();
    }
}
