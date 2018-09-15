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

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('posts', 'Front\PostController');
//TODO Not all
Route::resource('albums', 'Front\AlbumController');
Route::resource('goldenbook', 'Front\GoldenBookController');
Route::resource('contact', 'Front\ContactController');
Route::resource('cosplayers', 'Front\CosplayerController');

Route::get('/admin/dashboard', 'Back\AdminController@dashboard')->name('dashboard');
Route::resource('admin/albums', 'Back\AdminAlbumController', ['as' => 'admin']);
Route::resource('admin/contacts', 'Back\AdminContactController', ['as' => 'admin']);
Route::resource('admin/cosplayers', 'Back\AdminCosplayerController', ['as' => 'admin']);



