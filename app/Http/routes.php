<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/* GET */

Route::get('/', function () {
    return view('master');
});

Route::get('/auth', ['as'=>'login', 'uses'=>'RegistrationController@loginView']);

Route::get('/auth/registration', ['as'=>'registration', 'uses'=>'RegistrationController@registrationView']);

Route::get('/flowchart', ['as'=>'flowchart', 'middleware' => 'auth', 'uses'=>'FlowchartController@generateFlowChart']);

/* POST */

Route::post('auth/registration', ['as'=>'registrationForm', 'uses'=>'RegistrationController@newUserRegistration']);

/* AJAX */

Route::post('/auth/registration/get-majors', 'RegistrationController@getMajorsInFaculty');
