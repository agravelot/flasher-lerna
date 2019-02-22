<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:api', 'verified', 'admin'])->group(function () {
    Route::name('api.admin.')->group(function () {
        Route::prefix('admin')->group(function () {
            Route::apiResource('categories', 'CategoryController');
        });
    });
});
