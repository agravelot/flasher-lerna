<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Auth::routes(['verify' => true]);
Route::impersonate();

//FRONT
Route::get('/home', 'HomeController@index')->name('home');
Route::resource('posts', 'Front\PostController', ['only' => ['index', 'show']]);
Route::resource('albums', 'Front\AlbumController', ['only' => ['index', 'show']]);
Route::resource('goldenbook', 'Front\GoldenBookController', ['only' => ['index', 'show']]);
Route::resource('cosplayers', 'Front\CosplayerController', ['only' => ['index', 'show']]);
Route::resource('contact', 'Front\ContactController', ['only' => ['create', 'store']]);

//BACK
Route::namespace('Back')->group(function () {
    Route::name('admin.')->group(function () { # Route Name Prefixe
        Route::prefix('admin')->group(function () { # Route Prefixe /admin/
            Route::get('', 'AdminController@dashboard')->name('admin.dashboard');
            Route::resource('albums', 'AdminAlbumController');
            Route::resource('cosplayers', 'AdminCosplayerController');
            Route::resource('users', 'AdminUserController');
            Route::resource('contacts', 'AdminContactController', [
                'except' => ['edit', 'update']
            ]);
        });
    });
});




