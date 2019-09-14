<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

Auth::routes(['verify' => true]);
Route::impersonate();

//FRONT
Route::namespace('Front')->group(static function () {
    Route::resource('albums', 'AlbumController')->only(['index', 'show']);
    Route::resource('download-albums', 'DownloadAlbumController')->only(['show'])
        ->middleware(['auth', 'verified'])
        ->parameters([
            'download-albums' => 'album',
        ]);
    Route::resource('cosplayers', 'CosplayerController')->only(['index', 'show']);
    Route::resource('categories', 'CategoryController')->only(['index', 'show']);
    Route::resource('contact', 'ContactController')->only(['index', 'store']);
});

//BACK
Route::middleware(['web', 'auth', 'verified', 'admin'])->group(static function () {
    Route::name('admin.')->group(static function () {
        Route::prefix('admin')->group(static function () {
            Route::namespace('Admin')->group(static function () {
                Route::get('', 'AdminController')->name('dashboard');
                Route::resource('social-medias', 'AdminSocialMediaController')->except('show');
            });
            Route::get('/{any}', 'SpaController@index')->where('any', '.*');
        });
    });
});

Route::namespace('Front')->group(static function () {
    Route::get('/', 'HomeController')->name('home');
});

Route::namespace('Front')->group(static function () {
    Route::resource('contact', 'ContactController')->only(['index', 'store']);
});

//BACK
Route::middleware(['web', 'auth', 'verified', 'admin'])->group(static function () {
    Route::name('admin.')->group(static function () {
        Route::prefix('admin')->group(static function () {
            Route::resource('contacts', 'AdminContactController')->except('edit', 'update');
        });
    });
});

Route::prefix('cosplayer')->group(static function () {
    Route::get('/', 'AdminCosplayerController@index');
});

Route::namespace('Front')->group(static function () {
    Route::resource('testimonials', 'TestimonialController')->only(['index', 'create', 'store']);
});
