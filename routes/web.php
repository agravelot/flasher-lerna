<?php

Auth::routes(['verify' => true]);
Route::impersonate();
Route::feeds();

//FRONT
Route::namespace('Front')->group(static function () {
    Route::get('/', 'HomeController')->name('home');
    Route::resource('albums', 'AlbumController')->only(['index', 'show']);
    Route::resource('download-albums', 'DownloadAlbumController')->only(['show'])
        ->middleware(['auth', 'verified'])
        ->parameters([
            'download-albums' => 'album',
        ]);
    Route::resource('cosplayers', 'CosplayerController')->only(['index', 'show']);
    Route::resource('categories', 'CategoryController')->only(['index', 'show']);
    Route::resource('contact', 'ContactController')->only(['index', 'store']);
    Route::resource('testimonials', 'TestimonialController')->only(['index', 'create', 'store']);
});

//BACK
Route::middleware(['auth', 'verified', 'admin'])->group(static function () {
    Route::name('admin.')->group(static function () {
        Route::prefix('admin')->group(static function () {
            Route::namespace('Admin')->group(static function () {
                Route::resource('social-medias', 'AdminSocialMediaController')
                    ->except('show');
            });
            Route::namespace('Api')->group(static function () {
                Route::get('/{any?}', 'AdminDashboardController')
                    ->where('any', '.*')->name('dashboard');
            });
        });
    });
});
