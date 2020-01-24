<?php

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
    // Front
    Route::apiResource('albums', 'AlbumController')->only('index', 'show');
    Route::apiResource('categories', 'CategoryController')
        ->only('index', 'show');
    Route::resource('/account', 'AccountController')->middleware(['auth'])->only('destroy')
        ->parameters([
            'account' => 'user',
        ]);

    // Admin
    Route::group([
        'middleware' => ['auth:api', 'verified', 'admin'],
        'as' => 'admin.',
        'prefix' => 'admin',
    ], static function (): void {
        Route::get('dashboard', 'AdminDashboardController')->name('dashboard');
        Route::get('clear-cache', 'AdminClearCacheController')->name('clear-cache');

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
