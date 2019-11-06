<?php

Auth::routes(['verify' => true]);
Route::impersonate();
Route::feeds();

//FRONT
Route::namespace('Front')->group(static function (): void {
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
    Route::resource('invitations', 'InvitationController')->only(['show'])->middleware(['auth']);
    Route::get('/profile/my-albums', 'MyAlbumsController')->middleware(['auth']);
});

//BACK
Route::group([
    'middleware' => ['auth', 'verified', 'admin'],
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'Admin',
], static function (): void {
    Route::resource('social-medias', 'AdminSocialMediaController')->except('show');
    Route::get('/{any?}', 'AdminController')->where('any', '.*')->name('dashboard');
});
