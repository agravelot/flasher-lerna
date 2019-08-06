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
Route::namespace('Admin')->group(function () {
    Route::name('api.')->group(function () {
        Route::middleware(['auth:api', 'verified', 'admin'])->group(function () {
            Route::name('admin.')->group(function () {
                Route::prefix('admin')->group(function () {
                    Route::apiResource('testimonials', 'AdminTestimonialsController')->except('store');
                });
            });
        });
    });
});
