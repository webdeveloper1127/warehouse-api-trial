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
/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/





// User
// post to register a new user
Route::post('register', 'UserController@register');
// get to login in the system
Route::get('login', 'UserController@login');
	
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User
    // get list of users
    Route::get('users','UserController@index');
    // get specific user
    Route::get('user/{id}','UserController@show');    
    // update existing user
    Route::put('user','UserController@update');

    // Teachers
    // get list of teachers
    Route::get('teachers','UserController@listTeachers');
    // get specific teacher
    Route::get('teacher/{id}','UserController@showTeacher');
    // create new teacher
    Route::post('teacher','UserController@registerTeacher');
    // update existing teacher
    Route::put('teacher','UserController@updateTeacher');
    // delete a teacher
    Route::delete('teacher/{id}','UserController@destroyTeacher');

    // Students
    // get list of students
    Route::get('students','UserController@listStudents');
    // get specific student
    Route::get('student/{id}','UserController@showStudent');
    // create new student
    Route::post('student','UserController@registerStudent');
    // update existing student
    Route::put('student','UserController@updateStudent');
    // delete a student
    Route::delete('student/{id}','UserController@destroyStudent');

    // Classroooms
    // get list of classrooms
    Route::get('classrooms','ClassroomController@index');
    // get specific classroom
    Route::get('classroom/{id}','ClassroomController@show');
    // create new classroom
    Route::post('classroom','ClassroomController@register');
    // update existing classroom
    Route::put('classroom','ClassroomController@update');
    // delete a classroom
    Route::delete('classroom/{id}','ClassroomController@destroy');
});