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
Route::post('loginAdmin', 'API\AdminController@loginAdmin');
Route::post('registerAdmin', 'API\AdminController@registerAdmin');

Route::post('loginUser', 'API\UserController@loginUser');
Route::post('registerDoctor', 'API\UserController@registerDoctor');
Route::post('registerNurse', 'API\UserController@registerNurse');
Route::post('registerPatient', 'API\UserController@registerPatient');

Route::group([    
    'middleware' => 'auth:api', 
], function () {    
    Route::post('addNewActivity', 'API\HealthController@addNewActivity');
    Route::post('addNewHeartRate', 'API\HealthController@addNewHeartRate');
    
    Route::get('adminList', 'API\AdminController@getAdminList');
    Route::get('nurseList', 'API\UserController@getNurseList');
    Route::get('doctorList', 'API\UserController@getDoctorList');
    Route::get('patientList', 'API\UserController@getPatientList');
    Route::post('removeUser', 'API\UserController@removeUser');
    Route::get('getProfileInfo', 'API\UserController@getProfileInfo');
    Route::post('updateProfile', 'API\UserController@updateProfile');
    Route::post('updatePassword', 'API\UserController@updatePassword');
});

Route::group([    
    'namespace' => 'Auth',    
    'prefix' => 'password'
], function () {    
    Route::post('create', 'PasswordResetController@create');
    Route::post('reset/', 'PasswordResetController@reset');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
