<?php

//Route::get('/', function () {
//    return view('welcome');
//});
Auth::routes(['verify' => true]);
Route::impersonate();

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth', 'verified']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

//FRONT
Route::get('/home', 'HomeController@index')->name('home');

Route::namespace('Front')->group(function () {
    Route::resource('posts', 'PostController', ['only' => ['index', 'show']]);
    Route::resource('albums', 'AlbumController', ['only' => ['index', 'show']]);
    Route::get('/albums/{slug}/download', 'AlbumController@download')->name('album_download');
    Route::resource('goldenbook', 'GoldenBookController', ['only' => ['index', 'create', 'store']]);
    Route::resource('cosplayers', 'CosplayerController', ['only' => ['index', 'show']]);
    Route::resource('categories', 'CategoryController', ['only' => ['index', 'show']]);
    Route::resource('contact', 'ContactController', ['only' => ['create', 'store']]);
});

//BACK
Route::namespace('Admin')->group(function () {
    Route::name('admin.')->group(function () { # Route Name Prefixe
        Route::prefix('admin')->group(function () { # Route Prefixe /admin/
            Route::get('', 'AdminController@dashboard')->name('dashboard');
            Route::resource('albums', 'AdminAlbumController');
            Route::resource('categories', 'AdminCategoryController');
            Route::resource('goldenbook', 'AdminGoldenBookController');
            Route::resource('cosplayers', 'AdminCosplayerController');
            Route::resource('users', 'AdminUserController');
            Route::resource('contacts', 'AdminContactController', [
                'except' => ['edit', 'update']
            ]);
        });
    });
});




