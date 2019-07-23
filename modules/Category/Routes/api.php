<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

Route::name('api.')->group(function () {
    Route::apiResource('categories', 'CategoryController')->only(['index', 'show']);
    Route::middleware(['auth:api', 'verified', 'admin'])->group(function () {
        Route::name('admin.')->group(function () {
            Route::prefix('admin')->group(function () {
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
