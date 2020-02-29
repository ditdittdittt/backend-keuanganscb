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

// v1

Route::prefix('v1')->group(function () {

    // No need authenticate first
    Route::prefix('auth')->group(function () {
        Route::post('register', 'AuthController@register')->name('register');
        Route::post('login', 'AuthController@login')->name('login');

        Route::group(['middleware' => 'auth:api'], function () {
            Route::post('getUser', 'AuthController@getUser')->name('getUser');
            Route::post('logout', 'AuthController@logout')->name('logout');
        });
    });

    // Authenticate First
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', 'AuthController@logout')->name('logout');

        Route::prefix('form')->group(function () {

            // Form Request
            Route::prefix('request')->group(function () {
                Route::get('/', 'FormRequestController@index')->name('getAllFormRequests');
                Route::post('/store', 'FormRequestController@store')->name('storeFormRequest');
                Route::get('/detail', 'FormRequestController@detail')->name('getFormRequestDetail');
                Route::post('/update', 'FormRequestController@update')->name('updateFormRequest');
                Route::post('/delete', 'FormRequestController@destroy')->name('deleteFormRequest');
            });

            // Form Submission
            Route::prefix('submission')->group(function () {
                Route::get('/', 'FormSubmissionController@index')->name('getAllFormSubmission');
                Route::post('/store', 'FormSubmissionController@store')->name('storeFormSubmission');
                Route::get('/detail', 'FormSubmissionController@detail')->name('getFormSubmissionDetail');
                Route::post('/update', 'FormSubmissionController@update')->name('updateFormSubmission');
                Route::post('/delete', 'FormSubmissionController@delete')->name('deleteFormSubmission');
            });

            // Form PettyCash Header
            Route::prefix('petty_cash')->group(function () {
                Route::get('/', 'FormPettyCashController@index')->name('getAllFormSubmission');
                Route::post('/store', 'FormPettyCashController@store')->name('storeFormSubmission');
                Route::get('/detail', 'FormPettyCashController@detail')->name('getFormSubmissionDetail');
                Route::post('/update', 'FormPettyCashController@update')->name('updateFormSubmission');
                Route::post('/delete', 'FormPettyCashController@destroy')->name('deleteFormSubmission');
            });
        });
    });
});
