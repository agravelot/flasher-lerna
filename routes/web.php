<?php

Route::get('/', function () {
    return view('welcome');
});
Auth::routes(['verify' => true]);
Route::impersonate();

//FRONT
Route::get('/home', 'HomeController@index')->name('home');

Route::namespace('Front')->group(function () {
    Route::resource('posts', 'PostController', ['only' => ['index', 'show']]);
    Route::resource('albums', 'AlbumController', ['only' => ['index', 'show']]);
    Route::resource('goldenbook', 'GoldenBookController', ['only' => ['index', 'show']]);
    Route::resource('cosplayers', 'CosplayerController', ['only' => ['index', 'show']]);
    Route::resource('categories', 'CategoryController', ['only' => ['index', 'show']]);
    Route::resource('contact', 'ContactController', ['only' => ['create', 'store']]);
});

//BACK
Route::namespace('Back')->group(function () {
    Route::name('admin.')->group(function () { # Route Name Prefixe
        Route::prefix('admin')->group(function () { # Route Prefixe /admin/
            Route::get('', 'AdminController@dashboard')->name('dashboard');
            Route::resource('albums', 'AdminAlbumController');
            Route::resource('categories', 'AdminCategoryController');
            Route::resource('cosplayers', 'AdminCosplayerController');
            Route::resource('users', 'AdminUserController');
            Route::resource('contacts', 'AdminContactController', [
                'except' => ['edit', 'update']
            ]);
        });
    });
});




