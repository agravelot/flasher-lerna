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
Route::middleware(['web', 'auth', 'verified', 'admin'])->group(static function () {
    Route::name('admin.')->group(static function () {
        Route::prefix('admin')->group(static function () {
                Route::resource('contacts', 'AdminContactController')->except('edit', 'update');
            });
    });
});
