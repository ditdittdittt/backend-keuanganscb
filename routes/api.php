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

    Route::get('/email/resend', 'VerificationController@resend')->name('verification.resend');
    Route::get('/email/verify/{id}/{hash}', 'VerificationController@verify')->name('verification.verify');
    Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'ResetPasswordController@reset');

    Route::prefix('form')->group(function () {
        Route::prefix('request')->group(function () {
            Route::prefix('/export')->group(function () {
                Route::get('/excel', 'FormRequestController@exportExcel');
                Route::get('/pdf', 'FormRequestController@exportPdf');
            });
        });
    });

    // ROles
    Route::prefix('roles')->group(function () {
        Route::get('/', 'RoleAndPermissionController@getAllRoles')->name('getAllRoles');
        Route::post('/store', 'RoleAndPermissionController@storeRole')->name('storeRole');
    });

    // Authenticate First
    Route::group(['middleware' => 'auth:api'], function () {
        // User Side
        Route::prefix('form')->group(function () {

            // Form Request
            Route::prefix('request')->group(function () {
                Route::get('/', 'FormRequestController@index')->name('getAllFormRequests');
                Route::post('/', 'FormRequestController@store')->name('storeFormRequest');
                Route::group([
                    'prefix' => '{formRequest}',
                ], function () {
                    Route::get('/', 'FormRequestController@show')->name('showFormRequest');
                    Route::post('/', 'FormRequestController@update')->name('updateFormRequest');
                    Route::delete('/', 'FormRequestController@destroy')->name('deleteFormRequest');
                });
                Route::get('/count', 'FormRequestController@countRequestForm')->name('getCountFormRequests');
            });

            // Form Submission
            Route::prefix('submission')->group(function () {
                Route::get('/', 'FormSubmissionController@index')->name('getAllFormSubmission');
                Route::post('/', 'FormSubmissionController@store')->name('storeFormSubmission');
                Route::group([
                    'prefix' => '{formSubmission}',
                ], function () {
                    Route::get('/', 'FormSubmissionController@show')->name('getFormSubmissionDetail');
                    Route::post('/', 'FormSubmissionController@update')->name('updateFormSubmission');
                    Route::delete('/', 'FormSubmissionController@delete')->name('deleteFormSubmission');
                });
                Route::get('/count', 'FormSubmissionController@countSubmissionForm')->name('getCountFormSubmission');
            });

            // Form PettyCash Header
            Route::prefix('petty-cash')->group(function () {
                Route::get('/', 'FormPettyCashController@index')->name('getAllFormPettyCashes');
                Route::post('/', 'FormPettyCashController@store')->name('storePettyCash');
                Route::get('/count', 'FormPettyCashController@countFormPettyCash')->name('countFormPettyCash');
                Route::group([
                    'prefix' => '{formPettyCash}',
                ], function () {
                    Route::get('/', 'FormPettyCashController@show')->name('getFormPettyCash');
                    Route::post('/', 'FormPettyCashController@update')->name('updateFormPettyCash');
                    Route::delete('/', 'FormPettyCashController@destroy')->name('deleteFormPettyCash');
                    // Form PettyCash Detail
                    Route::prefix('/detail')->group(function () {
                        Route::get('/', 'FormPettyCashDetailController@index')->name('getAllPettyCashDetails');
                        Route::post('/', 'FormPettyCashDetailController@store')->name('storePettyCashDetail');
                        Route::group([
                            'prefix' => '{formPettyCashDetail}',
                        ], function () {
                            Route::get('/', 'FormPettyCashDetailController@show')->name('getPettyCashDetail');
                            Route::post('/', 'FormPettyCashDetailController@update')->name('updatePettyCashDetail');
                            Route::delete('/', 'FormPettyCashDetailController@destroy')->name('deletePettyCashDetail');
                        });
                    });
                });
            });
        });

        Route::prefix('budget-code')->group(function () {
            Route::get('/', 'BudgetCodeController@index')->name('getAllBudgetCode');
            Route::post('/', 'BudgetCodeController@store')->name('storeBudgetCode');
            Route::group([
                'prefix' => '{budgetCode}',
                'where' => ['budgetCode' => '[0-9]+']
            ], function () {
                Route::get('/', 'BudgetCodeController@show');
                Route::post('/', 'BudgetCodeController@update');
                Route::delete('/', 'BudgetCodeController@destroy');
            });
        });

        // Admin Side
        Route::group(['middleware' => ['role:admin']], function () {

            // Users
            Route::prefix('users')->group(function () {
                Route::get('/', 'UserController@getAllUserWithAllTheirRolesAndPermissions')->name('getAllUserWithAllTheirRolesAndPermissions');
                Route::post('/{user}', 'UserController@update')->name('updateUser');
                Route::get('/count', 'UserController@count')->name('countUser');
                Route::post('/assign-role', 'UserController@assignRole')->name('assignRoleByUser');
                Route::post('/change-role', 'UserController@changeRole')->name('changeUserRole');
                Route::post('/remove-role', 'UserController@removeRole')->name('removeUserRole');
                Route::post('/give-permission', 'UserController@givePermission')->name('assignPermissionToUser');
                Route::post('/revoke-permission', 'UserController@revokePermission')->name('revokeUserPermission');
            });


            // Permissions
            Route::prefix('permissions')->group(function () {
                Route::get('/', 'RoleAndPermissionController@getAllPermissions')->name('getAllPermissions');
                Route::post('/store', 'RoleAndPermissionController@storePermission')->name('storePermission');
                Route::get('/by-role', 'RoleAndPermissionController@getPermissionsByRole')->name('getPermissionsByRole');
                Route::post('/assign-to-role', 'RoleAndPermissionController@assignPermissionToRole')->name('assignPermissionToRole');
                Route::post('/revoke-from-role', 'RoleAndPermissionController@revokePermissionFromRole')->name('revokePermissionFromRole');
            });
        });
    });
    Route::get('/roles', 'RoleAndPermissionController@getAllRoles')->name('getAllRoles');


    Route::prefix('export')->group(function () {
        Route::prefix('form-request')->group(function () {
            Route::get('{formRequest}/pdf', 'FormRequestController@exportSinglePdf')->name('exportSingleFormRequestPdf');
            Route::get('/excel', 'FormRequestController@exportExcel');
            Route::get('/pdf', 'FormRequestController@exportPdf');
        });
        Route::prefix('form-petty-cash')->group(function () {
            Route::get('{formPettyCash}/pdf', 'FormPettyCashController@exportSinglePdf')->name('exportSingleFormPettyCashPdf');
            Route::get('/excel', 'FormPettyCashController@exportExcel');
            Route::get('/pdf', 'FormPettyCashController@exportPdf');
        });
        Route::prefix('form-submission')->group(function () {
            Route::get('{formSubmission}/pdf', 'FormSubmissionController@exportSinglePdf')->name('exportSingleFormSubmissionPdf');
            Route::get('/excel', 'FormSubmissionController@exportExcel');
            Route::get('/pdf', 'FormSubmissionController@exportPdf');
        });
    });
});
