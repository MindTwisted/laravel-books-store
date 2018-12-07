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

Route::group(['namespace' => 'Api'], function() {
    Route::name('api.')->group(function () {
        
        Route::post('register', 'UserController@register')->name('users.register');
        Route::post('login', 'UserController@authenticate')->name('users.login');

        Route::resource('authors', 'AuthorController')->only([
            'index', 'show'
        ]);

        Route::group(['middleware' => ['jwt.verify']], function() {
            Route::get('user', 'UserController@getAuthenticatedUser')->name('users.current');

            Route::resource('authors', 'AuthorController')->only([
                'store', 'update', 'destroy'
            ]);
        });

    });
});