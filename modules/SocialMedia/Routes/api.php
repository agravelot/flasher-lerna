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

Route::name('api.')->group(static function () {
    Route::middleware(['auth:api', 'verified', 'admin'])->group(static function () {
        Route::name('admin.')->group(static function () {
            Route::prefix('admin')->group(static function () {
                Route::apiResource('social-medias', 'AdminSocialMediaController');
            });
        });
    });
});
