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

Route::post('login', 'API\Auth\PassportController@login');
Route::post('register', 'API\Auth\PassportController@register');

Route::middleware('auth:api')->group(function () {
    Route::get('user', 'API\Auth\PassportController@details');
    Route::get('logout', 'API\Auth\PassportController@logout');
});

Route::apiResource('bookmarks', 'API\BookmarksController');

// Verification Routes
Route::get('email/verify/{id}','API\Auth\VerificationController@verify')->name('user.verify');
Route::get('email/resend/{id}', 'API\Auth\VerificationController@resend')->name('user.resend');
