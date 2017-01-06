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
    return view('landing.landing');
});

Route::get('/login', ['as'=>'loginView', 'uses'=>'RegistrationController@loginView']);

Route::get('/logout', ['as'=>'logout', 'uses'=>'RegistrationController@logout']);

Route::get('/auth/registration', ['as'=>'registration','uses'=>'RegistrationController@registrationView']);

Route::get('/flowchart', ['as'=>'flowchart', 'middleware' => 'auth', 'uses'=>'FlowchartController@generateFlowChart']);

Route::get('password/email', ['as'=>'passwordEmailGet','uses'=>'Auth\PasswordController@getEmail']);

Route::get('password/reset/{token}', ['as'=>'passwordResetPost','uses'=>'Auth\PasswordController@getReset']);

Route::get('flowchart/reset-degree', ['as' => 'resetDegree', 'middleware' => 'auth', 'uses' => 'DegreeController@deleteDegree']);

Route::get('/flowchart/remove-minor', ['as'=> 'removeMinor', 'middleware' => 'auth', 'uses'=> 'MinorController@removeMinor']);


/* POST */

Route::post('auth/registration', ['as'=>'registrationForm', 'uses'=>'RegistrationController@newUserRegistration']);

Route::post('/auth/login', ['as'=>'login', 'uses'=>'RegistrationController@login']);

Route::post('password/email', ['as'=>'passwordEmailPost', 'uses'=>'Auth\PasswordController@postEmail']);

Route::post('password/reset', ['as'=>'passwordResetPost','uses'=>'Auth\PasswordController@postReset']);

Route::post('/flowchart/new-user-create-degree', ['as'=>'newUserCreateDegree', 'uses'=>'FlowchartController@newUserCreateDegree']);

Route::post('/flowchart/user-create-course', ['as'=>'userCreateCourse', 'middleware' => 'auth', 'uses'=>'FlowchartAJAX@userCreateCourse']);

Route::post('/flowchart/add-minor', ['as'=>'addMinor', 'middleware' => 'auth', 'uses'=>'MinorController@addMinor']);

Route::post('/flowchart/get-courses-in-semester', ['as' => 'getCoursesInSemester', 'uses' => 'FlowchartAJAX@get_courses_in_semester']);


/* AJAX */

Route::post('/auth/registration/get-majors', 'DegreeController@getMajorsInFaculty');

Route::post('/auth/registration/get-versions', 'DegreeController@getProgramVersionsInMajor');

Route::post('/auth/registration/get-streams', 'DegreeController@getProgramStreams');

Route::post('/auth/registration/get-semesters', 'DegreeController@getSemesters');

Route::post('/flowchart/move-course', 'FlowchartAJAX@move_course');

Route::post('/flowchart/add-course-to-Schedule', 'FlowchartAJAX@add_course_to_Schedule');

Route::get('/flowchart/get-elective-groups', 'FlowchartAJAX@getElectiveGroups');

Route::delete('/flowchart/delete_course_from_schedule', 'FlowchartAJAX@delete_course_from_schedule');

Route::get('/flowchart/refresh-complementary-courses', 'FlowchartAJAX@refresh_complementary_courses');

Route::post('/flowchart/check-course-availability','FlowchartAJAX@vsb_checkCourseAvailablity');

Route::post('/flowchart/add_complementary_course_to_Flowchart', 'FlowchartAJAX@add_complementary_course_to_Flowchart');

Route::post('/flowchart/edit-internship', 'FlowchartAJAX@edit_internship');

Route::post('/flowchart/edit-custom', 'FlowchartAJAX@edit_custom');

Route::post('/flowchart/getErrors', 'FlowchartAJAX@get_errors');

Route::post('/flowchart/remainingCourses','FlowchartAJAX@getMajorStatus_ajax');

Route::post('flowchart/ignore-error', 'FlowchartAJAX@ignore_error');

Route::post('flowchart/check-for-ignored-errors', 'FlowchartAJAX@check_for_ignored_errors');

Route::post('/flowchart/reveal-errors', 'FlowchartAJAX@reveal_errors');