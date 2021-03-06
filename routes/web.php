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

Auth::routes(['verify' => true]);

Route::get('home', 'HomeController@index')->name('home');

Route::resource('bookmarks', 'BookmarksController');
Route::match(['put', 'patch'], 'bookmarks/{bookmark}/tag/attach', 'BookmarksController@attachTag')->name('bookmarks.tag.attach');
Route::delete('bookmarks/{bookmark}/tag/{tag}/detach', 'BookmarksController@detachTag')->name('bookmarks.tag.detach');

// Route for users
Route::resource('users', 'UsersController')->except('destroy');
Route::delete('users/{user}', 'UsersController@suspend')->name('users.suspend');
Route::match(['get', 'head'], 'password/change', 'UsersController@showChangePasswordForm')->name('password.change.request');
Route::match(['put', 'patch'], 'password/change', 'UsersController@changePassword')->name('password.change');
Route::match(['put', 'patch'], 'users/{user}/role', 'UsersController@assignRole')->name('users.role.assign');
// Route::match(['get', 'head'], 'users', 'UsersController@index')->name("users.index");
// Route::delete('users/{user}', 'UsersController@suspend')->name('users.suspend');
// Route::match(['put', 'patch'], 'users/{user}', 'UsersController@update')->name('users.update');
// Route::match(['put', 'patch'], 'users/{user}/profile', 'UsersController@updateProfile')->name('profiles.update');
// Route::match(['put', 'patch'], 'users/{user}/elevate', 'UsersController@updateElevated')->name('users.update.elevate');
// Route::match(['put', 'patch'], 'users/{user}/role', 'UsersController@assignRole')->name('users.update.role');
// Route::match(['get', 'head'], 'users/{user}', 'UsersController@show')->name('users.show');
// Route::match(['get', 'head'], 'users/{user}/edit', 'UsersController@edit')->name('users.edit');

// Route for assigning and removing tags
Route::match(['get', 'head'], 'tags', 'TagsController@index')->name('tags.index');
Route::match(['get', 'head'], 'tags/{tag}', 'TagsController@show')->name('tags.show');
Route::delete('/tags/{tag}', 'TagsController@destroy')->name('tags.destroy');
