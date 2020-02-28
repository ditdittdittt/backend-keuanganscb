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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

    // No need authenticate first
    Route::prefix('auth')->group(function () {
        Route::post('register', 'AuthController@register')->name('register');
        Route::post('login', 'AuthController@login')->name('login');
    });

    // Authenticate First
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', 'AuthController@logout')->name('logout');

        // Form Request
        Route::prefix('form')->group(function () {
            Route::prefix('request')->group(function () {
                Route::get('/', 'FormRequestController@index')->name('getAllFormRequests');
                Route::post('/store', 'FormRequestController@store')->name('storeFormRequest');
                Route::get('/detail', 'FormRequestController@detail')->name('getFormRequestDetail');
                Route::post('/update', 'FormRequestController@update')->name('updateFormRequest');
                Route::post('/delete', 'FormRequestController@destroy')->name('deleteFormRequest');
            });
        });
    });
});
