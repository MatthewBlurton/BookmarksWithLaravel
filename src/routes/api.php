<?php

use Illuminate\Http\Request;

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


Route::name('api.')->group(function() {
    // User account routes
    Route::middleware('guest')->group(function () {
        Route::post('login', 'API\Auth\PassportController@login')->name('login');
        Route::post('register', 'API\Auth\PassportController@register')->name('register');
    });

    Route::middleware('auth:api')->group(function () {
        Route::get('user', 'API\Auth\PassportController@details')->name('user');
        Route::get('logout', 'API\Auth\PassportController@logout')->name('logout');
    });

    // Verification Routes
    Route::match(['get', 'head'], 'email/verify/{id}', 'API\Auth\VerificationController@verify')->name('verification.verify');
    Route::match(['get', 'head'], 'email/resend', 'API\Auth\VerificationController@resend')->name('verification.resend');

    Route::apiResource('bookmarks', 'API\BookmarksController');
    Route::match(['put', 'patch'], 'bookmarks/{bookmark}/tag/attach', 'API\BookmarksController@attachTag')->name('bookmarks.tag.attach');
    Route::delete('bookmarks/{bookmark}/tag/{tag}/detach', 'API\BookmarksController@detachTag')->name('bookmarks.tag.detach');

    // Tag routes
    Route::name('tags.')->group(function() {
        Route::match(['get', 'head'], 'tags', 'API\TagsController@index')->name('index');
        Route::match(['get', 'head'], 'tags/{tag}', 'API\TagsController@show')->name('show');
        Route::delete('tags/{tag}', 'Api\TagsController@destroy')->name('destroy');
    });
});
