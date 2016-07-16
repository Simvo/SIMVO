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
    header("Location: http://www.wearesimvo.com/");
    exit;
});

Route::get('/login', ['as'=>'loginView', 'uses'=>'RegistrationController@loginView']);

Route::get('/logout', ['as'=>'logout', 'uses'=>'RegistrationController@logout']);

Route::get('/auth/registration', ['as'=>'registration', 'uses'=>'RegistrationController@registrationView']);

Route::get('/flowchart', ['as'=>'flowchart', 'middleware' => 'auth', 'uses'=>'FlowchartController@generateFlowChart']);

/* POST */

Route::post('auth/registration', ['as'=>'registrationForm', 'uses'=>'RegistrationController@newUserRegistration']);

Route::post('/auth/login', ['as'=>'login', 'uses'=>'RegistrationController@login']);

/* AJAX */

Route::post('/auth/registration/get-majors', 'RegistrationController@getMajorsInFaculty');

Route::post('/flowchart/move-course', 'FlowchartAJAX@move_course');

Route::post('/flowchart/add-course-to-Schedule', 'FlowchartAJAX@add_course_to_Schedule');

Route::delete('/flowchart/delete_course_from_schedule', 'FlowchartAJAX@delete_course_from_schedule');

Route::get('/flowchart/refresh_complementary_courses', 'FlowchartAJAX@refresh_complementary_courses');


Route::post('/flowchart/check-course-availability','FlowchartAJAX@vsb_checkCourseAvailablity');

Route::post('/flowchart/add_complementary_course_to_Flowchart', 'FlowchartAJAX@add_complementary_course_to_Flowchart');
