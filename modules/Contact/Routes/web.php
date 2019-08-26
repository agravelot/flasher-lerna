<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

Route::resource('contact', 'ContactController')->only(['index', 'store']);

//BACK
Route::middleware(['web', 'auth', 'verified', 'admin'])->group(static function () {
    Route::name('admin.')->group(static function () {
        Route::prefix('admin')->group(static function () {
            Route::resource('contacts', 'AdminContactController')->except('edit', 'update');
        });
    });
});
