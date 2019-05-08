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
Route::namespace('Front')->group(function () {
    Route::resource('albums', 'AlbumController')->only(['index', 'show']);
    Route::resource('download-albums', 'DownloadAlbumController')->only(['show'])
        ->middleware('auth', 'verified')
        ->parameters([
            'download-albums' => 'album',
        ]);
    Route::resource('goldenbook', 'GoldenBookController')->only(['index', 'create', 'store']);
    Route::resource('cosplayers', 'CosplayerController')->only(['index', 'show']);
    Route::resource('categories', 'CategoryController')->only(['index', 'show']);
    Route::resource('contact', 'ContactController')->only(['index', 'store']);
});

//BACK
Route::middleware(['web', 'auth', 'verified', 'admin'])->group(function () {
    Route::name('admin.')->group(function () {
        Route::prefix('admin')->group(function () {
            Route::namespace('Admin')->group(function () {
                Route::get('', 'AdminController')->name('dashboard');
                Route::resource('albums', 'AdminAlbumController');
                Route::resource('categories', 'AdminCategoryController');
                Route::resource('goldenbook', 'AdminGoldenBookController');
                Route::resource('social-medias', 'AdminSocialMediaController')->except('show');
                Route::resource('published-goldenbook', 'AdminPublishedGoldenBookController')->only('store', 'destroy');
                Route::resource('cosplayers', 'AdminCosplayerController');
                Route::resource('users', 'AdminUserController');
                Route::resource('contacts', 'AdminContactController')->except('edit', 'update');
            });
            Route::get('/spa/{any}', 'SpaController@index')->where('any', '.*');
        });
    });
});
