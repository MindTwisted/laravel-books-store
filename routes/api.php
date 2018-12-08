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

// Routes in Api namespace
Route::group(['namespace' => 'Api'], function() {

    // Routes with api. names
    Route::name('api.')->group(function () {
        
        // Guest routes
        // Login route
        Route::put('auth', 'AuthController@login')->name('auth.login');

        // Register route
        Route::post('auth', 'AuthController@register')->name('auth.register');

        // Authors routes
        Route::resource('authors', 'AuthorController')->only([
            'index', 'show'
        ]);

        // Genres routes
        Route::resource('genres', 'GenreController')->only([
            'index', 'show'
        ]);

        // Auth protected routes
        Route::group(['middleware' => ['jwt.verify']], function() {

            // User info route
            Route::get('auth', 'AuthController@current')->name('auth.current');

            // Auth and Admin protected routes
            Route::group(['middleware' => 'admin'], function() {

                // Authors routes
                Route::resource('authors', 'AuthorController')->only([
                    'store', 'update', 'destroy'
                ]);

                // Genres routes
                Route::resource('genres', 'GenreController')->only([
                    'store', 'update', 'destroy'
                ]);
                
            });

        });

    });

});