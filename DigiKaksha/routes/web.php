<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::resource('groups', 'GroupsController');
Route::resource('courses', 'CoursesController');
Route::resource('students', 'StudentsController');
Route::resource('teachers', 'TeachersController');
Route::resource('admins', 'AdminsController');
Route::resource('announcements', 'AnnouncementsController');
Route::resource('submissions', 'SubmissionsController');



//Attedance routes -  Note Security aspects are not added. 
Route::POST('/attendance/request/{cid}','attendanceController@processRequest');
Route::GET('/attendance/mark/{cid}/{gid}','attendanceController@showForm');
Route::POST('/attendance/mark/{cid}/{gid}','attendanceController@markAttendance');


