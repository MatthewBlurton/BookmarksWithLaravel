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

Route::post('login', 'API\PassportController@login');
Route::post('register', 'API\PassportController@register');

Route::middleware('auth:api')->group(function () {
    Route::get('user', 'API\PassportController@details');
});

// Route::match(['get', 'head'], 'bookmarks', 'API\BookmarksController@index')->name('api.bookmarks');
Route::apiResource('bookmarks', 'API\BookmarksController');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
