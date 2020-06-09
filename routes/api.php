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
    Route::get('fetchPatientActivities', 'API\HealthController@fetchPatientActivities');
    Route::get('fetchPatientHeartRates', 'API\HealthController@fetchPatientHeartRates');
    
    Route::get('adminList', 'API\AdminController@getAdminList');
    Route::get('nurseList', 'API\UserController@getNurseList');
    Route::get('doctorList', 'API\UserController@getDoctorList');
    Route::get('getLast24HoursUsers', 'API\UserController@getLast24HoursUsers');
    Route::get('registrationsPerMonth', 'API\UserController@registrationsPerMonth');
    Route::get('fetchDoctorListBySpecialty', 'API\UserController@doctorListBySpecialty');
    Route::post('affectDoctorPatient', 'API\UserController@affectDoctorPatient');
    Route::post('sendAffectRequest', 'API\UserController@sendAffectRequest');
    Route::post('checkAffectDoctorPatient', 'API\UserController@checkAffectDoctorPatient');
    Route::post('checkAffectDoctorNurse', 'API\UserController@checkAffectDoctorNurse');
    Route::post('sendAppointmentRequest', 'API\UserController@sendAppointmentRequest');
    Route::post('affectDoctorNurse', 'API\UserController@affectDoctorNurse');
    Route::post('sendAffectRequestDoctorNurse', 'API\UserController@sendAffectRequestDoctorNurse');
    Route::get('fetchAffectRequests', 'API\UserController@fetchAffectRequests');
    Route::get('fetchAppointmentRequests', 'API\UserController@fetchAppointmentRequests');
    Route::get('fetchNurseAppointmentRequests', 'API\UserController@fetchNurseAppointmentRequests');
    Route::get('fetchAffectRequestsDoctorNurse', 'API\UserController@fetchAffectRequestsDoctorNurse');
    Route::post('acceptAffectRequest', 'API\UserController@acceptAffectRequest');
    Route::post('denyAffectRequest', 'API\UserController@denyAffectRequest');
    Route::post('denyAppointmentRequest', 'API\UserController@denyAppointmentRequest');
    Route::post('acceptAffectRequestDoctorNurse', 'API\UserController@acceptAffectRequestDoctorNurse');
    Route::post('denyAffectRequestDoctorNurse', 'API\UserController@denyAffectRequestDoctorNurse');
    Route::get('patientList', 'API\UserController@getPatientList');
    Route::get('doctorPatientList', 'API\UserController@getDoctorPatientList');
    Route::get('patientDoctorList', 'API\UserController@getPatientDoctorList');
    Route::get('doctorNurseList', 'API\UserController@getDoctorNurseList');
    Route::get('nurseDoctorList', 'API\UserController@getNurseDoctorList');
    Route::get('nursePatientList', 'API\UserController@nursePatientList');
    Route::delete('removeUser', 'API\UserController@removeUser');
    Route::delete('removeAffect', 'API\UserController@removeAffectDoctorPatient');
    Route::delete('removeAffectDoctorNurse', 'API\UserController@removeAffectDoctorNurse');
    Route::get('getProfileInfo', 'API\UserController@getProfileInfo');
    Route::get('getProfileInfoUsingEmail', 'API\UserController@getProfileInfoUsingEmail');
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
