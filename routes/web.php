<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

use UniSharp\LaravelFilemanager\Lfm;

Auth::routes(['verify' => true]);
Route::impersonate();

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth', 'verified']], function () {
    Lfm::routes();
});

//FRONT
Route::get('/', 'HomeController@index')->name('home');

Route::namespace('Front')->group(function () {
    Route::resource('posts', 'PostController')->only(['index', 'show']);
    Route::resource('albums', 'AlbumController')->only(['index', 'show']);
    Route::resource('download-albums', 'DownloadAlbumController')->only(['show'])->middleware('auth', 'verified');
    Route::resource('goldenbook', 'GoldenBookController')->only(['index', 'create', 'store']);
    Route::resource('cosplayers', 'CosplayerController')->only(['index', 'show']);
    Route::resource('categories', 'CategoryController')->only(['index', 'show']);
    Route::resource('contact', 'ContactController')->only(['index', 'store']);
});

//BACK
Route::group(['middleware' => ['web', 'auth', 'verified', 'admin']], function () {
    Route::namespace('Admin')->group(function () {
        Route::name('admin.')->group(function () { // Route Name Prefixe
            Route::prefix('admin')->group(function () { // Route Prefixe /admin/
                Route::get('', 'AdminController@dashboard')->name('dashboard');
                Route::resource('albums', 'AdminAlbumController');
                Route::resource('album-pictures', 'AdminPictureAlbumController')->only('store');
                Route::resource('categories', 'AdminCategoryController');
                Route::resource('goldenbook', 'AdminGoldenBookController');
                Route::resource('published-goldenbook', 'AdminPublishedGoldenBookController')->only('store', 'destroy');
                Route::resource('cosplayers', 'AdminCosplayerController');
                Route::resource('users', 'AdminUserController');
                Route::resource('contacts', 'AdminContactController')->except('edit', 'update');
            });
        });
    });
});
