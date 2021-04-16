<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group([
    'namespace' => 'Api',
    'as' => 'api.',
], static function (): void {
    // Public
    Route::apiResource('albums', 'AlbumController')->only('index', 'show');
    Route::apiResource('cosplayers', 'CosplayerController')->only('index', 'show');
    Route::apiResource('testimonials', 'TestimonialController')->only('index');
    Route::apiResource('categories', 'CategoryController')
        ->only('index', 'show');
    Route::apiResource('settings', 'SettingsController');
    Route::resource('contact', 'ContactController')->only(['store']);
    Route::apiResource('social-medias', 'SocialMediaController')->only(['index']);

//    Route::resource('/account', 'AccountController')->middleware(['auth'])->only('destroy')
//        ->parameters([
//            'account' => 'user',
//        ]);

    // User
    Route::get('/me/albums', 'MyAlbumsController')->middleware(['auth:api', 'verified'])->name('me.my-albums');
    Route::resource('download-albums', 'DownloadAlbumController')->only(['show'])
        ->middleware(['signed'])
        ->parameters([
            'download-albums' => 'album',
        ]);
    Route::get('generate-download-albums/{album}', 'GenerateDownloadAlbumLinkController')
        ->middleware(['auth:api', 'verified']);

    Route::get('invitations/{invitation}/accept', 'AcceptInvitationController')
        ->middleware(['auth:api', 'verified'])
        ->where('invitation', '^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$');

    // Admin
    Route::group([
        'middleware' => ['auth:api', 'verified', 'admin'],
        'as' => 'admin.',
        'prefix' => 'admin',
    ], static function (): void {
        Route::get('dashboard', 'AdminDashboardController')->name('dashboard');
        Route::get('clear-cache', 'AdminClearCacheController')->name('clear-cache');

        Route::apiResource('album-media-added', 'AdminMediaWebhook')->only(['store']);
        Route::apiResource('albums', 'AdminAlbumController');
        Route::patch('albums/{album}/media-ordering', 'AlbumMediaOrderingController')
            ->name('albums.media-ordering');
        Route::apiResource('album-pictures', 'AdminPictureAlbumController')
            ->only('store', 'destroy')
            ->parameters(['album-pictures' => 'album']);
        Route::apiResource('categories', 'AdminCategoryController');
        Route::apiResource('cover-categories', 'AdminCoverCategoryController')
            ->only('store', 'destroy')
            ->parameters(['cover-categories' => 'category']);
        Route::apiResource('pages', 'AdminPagesController');
        Route::apiResource('settings', 'AdminSettingsController')
            ->only('index', 'show', 'update');
        Route::apiResource('users', 'AdminUsersController');
        Route::apiResource('social-medias', 'AdminSocialMediaController');
        Route::apiResource('testimonials', 'AdminTestimonialsController')
            ->except('store');
        Route::apiResource('contacts', 'AdminContactController')
            ->except('store', 'update');
        Route::apiResource('cosplayers', 'AdminCosplayerController');
        Route::apiResource('invitations', 'AdminInvitationController')
            ->except('update');
    });
});
