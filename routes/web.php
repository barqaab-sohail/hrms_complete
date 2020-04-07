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

Route::get('/dashboard', function () {
    
    return view('dashboard.dashboard');
});

Route::get('/hrms/testing', function () {
	
    return view('dashboard.dashboard1');
});


Auth::routes();

Route::prefix('code')->namespace('Auth')->name('otp.')->group(function(){
Route::resource('/','RegisterController',['only'=>['create','store']]);
});


// Route::prefix('hrms/')->namespace('Hr')->group(function(){
// Route::resource('/employee', 'EmployeeController');
// Route::resource('/education', 'EducationController');
// Route::resource('/appointment', 'AppointmentController');

// });

Route::group(['prefix' => 'hrms', 'middleware' => 'auth', 'namespace'=>'Hr'], function(){
Route::resource('/employee', 'EmployeeController');
Route::resource('/education', 'EducationController');
Route::resource('/appointment', 'AppointmentController',['only'=>['edit','update']]);
Route::resource('/salary', 'SalaryController',['only'=>['store']]);

});






Route::get('/home', 'HomeController@index')->name('home');
