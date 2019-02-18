<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

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

//TODO Implement JWT auth
Route::middleware('auth:api')->group(function () {
    Route::name('api.admin.')->group(function () { // Route Name Prefixe
        Route::prefix('admin')->group(function () { // Route Prefixe /admin/
            Route::namespace('Api\Admin')->group(function () {
                Route::apiResource('albums', 'AdminAlbumController');
            });
            Route::namespace('Admin')->group(function () {
                Route::apiResource('album-pictures', 'AdminPictureAlbumController')->only('store', 'destroy');
            });
        });
    });
});
