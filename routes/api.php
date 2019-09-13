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

Route::namespace('Api')->group(static function () {
    Route::name('api.')->group(static function () {
        Route::apiResource('albums', 'AlbumController')->only('index', 'show');
    });

    Route::middleware(['auth:api', 'verified', 'admin'])->group(static function () {
        Route::name('api.admin.')->group(static function () {
            Route::prefix('admin')->group(static function () {
                Route::apiResource('albums', 'AdminAlbumController');
                Route::apiResource('album-pictures', 'AdminPictureAlbumController')
                    ->only('store', 'destroy')
                    ->parameters([
                        'album-pictures' => 'album',
                    ]);
            });
        });
    });
});

Route::name('api.')->group(static function () {
    Route::apiResource('categories', 'CategoryController')->only(['index', 'show']);
    Route::middleware(['auth:api', 'verified', 'admin'])->group(static function () {
        Route::name('admin.')->group(static function () {
            Route::prefix('admin')->group(static function () {
                Route::apiResource('categories', 'AdminCategoryController');
                Route::apiResource('cover-categories', 'AdminCoverCategoryController')
                    ->only(['store', 'destroy'])
                    ->parameters([
                        'cover-categories' => 'category',
                    ]);
            });
        });
    });
});

Route::namespace('Admin')->group(static function () {
    Route::middleware(['auth:api', 'verified', 'admin'])->group(static function () {
        Route::name('api.admin.')->group(static function () {
            Route::prefix('admin')->group(static function () {
                Route::apiResource('pages', 'AdminPagesController');
                Route::apiResource('settings', 'AdminSettingsController')->only(['index', 'show', 'update']);
            });
        });
    });
});
