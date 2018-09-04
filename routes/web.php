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
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('posts', 'Front\PostController');
Route::resource('albums', 'Front\AlbumController');
Route::resource('goldenbook', 'Front\GoldenBookController');
Route::resource('contact', 'Front\ContactController');

Route::get('/admin/dashboard', 'Back\AdminController@dashboard')->name('dashboard');
Route::get('/admin/albums', 'Back\AdminController@albums')->name('admin_albums');
Route::get('/admin/contacts', 'Back\AdminController@contacts')->name('admin_contacts');
