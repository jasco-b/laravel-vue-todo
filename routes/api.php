<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::namespace('Api')->name('api.')->middleware(['api'])->group(function () {


    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('login', 'AuthController@login')->name('login');
        Route::post('logout', 'AuthController@logout')->name('logout');
        Route::post('refresh', 'AuthController@refresh')->name('refresh');
    });


    Route::middleware('auth:api')->group(function () {


        Route::put('users/{user}/todos/{todo}/complete', 'TodoController@makeCompleted')
            ->middleware(['can:todoAction,user','can:complete,todo',])
            ->name('users.tasks.complete');

        Route::apiResource('users.todos', 'TodoController')
            ->middleware('can:todoAction,user')
            ->names([
                'index' => 'users.tasks.index',
                'store' => 'users.tasks.store',
                'update' => 'users.tasks.update',
                'destroy' => 'users.tasks.destroy',
            ]);


        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', 'ProfileController@show')->name('show');
            Route::put('/', 'ProfileController@update')->name('update');
        });

    });

});
