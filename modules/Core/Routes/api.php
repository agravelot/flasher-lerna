<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

Route::middleware(['auth:api', 'verified', 'admin'])->group(function () {
    Route::name('api.admin.')->group(function () {
        Route::prefix('admin')->group(function () {
            Route::apiResource('settings', 'AdminSettingsController')->only(['index', 'show', 'update']);
        });
    });
});
