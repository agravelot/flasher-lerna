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

Route::resource('contact', 'ContactController')->only(['index', 'store']);


//BACK
Route::middleware(['web', 'auth', 'verified', 'admin'])->group(function () {
    Route::name('admin.')->group(function () {
        Route::prefix('admin')->group(function () {
                Route::resource('contacts', 'AdminContactController')->except('edit', 'update');
            });
    });
});
