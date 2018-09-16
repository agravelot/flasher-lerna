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

//FRONT
Route::get('/home', 'HomeController@index')->name('home');
Route::resource('posts', 'Front\PostController', ['only' => ['index', 'show']]);
Route::resource('albums', 'Front\AlbumController', ['only' => ['index', 'show']]);
Route::resource('goldenbook', 'Front\GoldenBookController', ['only' => ['index', 'show']]);
Route::resource('cosplayers', 'Front\CosplayerController', ['only' => ['index', 'show']]);
Route::resource('contact', 'Front\ContactController', ['only' => ['create', 'store']]);

//BACK
Route::get('admin/dashboard', 'Back\AdminController@dashboard')->name('dashboard');
Route::resource('admin/albums', 'Back\AdminAlbumController', ['as' => 'admin']);
Route::resource('admin/cosplayers', 'Back\AdminCosplayerController', ['as' => 'admin']);
Route::resource('admin/users', 'Back\AdminUserController', ['as' => 'admin']);
Route::resource('admin/contacts', 'Back\AdminContactController', [
    'as' => 'admin',
    'only' => ['index', 'delete']
]);



