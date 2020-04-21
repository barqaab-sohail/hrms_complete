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
Route::get('/contact/refreshTable', 'ContactController@refreshTable')->name('contact.table');
Route::resource('/contact', 'ContactController');
Route::get('/documentation/refreshTable', 'DocumentationController@refreshTable')->name('documentation.table');
Route::resource('/documentation', 'DocumentationController');
Route::get('/userLogin/refreshTable', 'UserLoginController@refreshTable')->name('userLogin.table');
Route::resource('/userLogin', 'UserLoginController',['only'=>['edit','store','destroy']]);
Route::resource('/picture', 'PictureController',['only'=>['edit','store']]);


});

Route::group(['prefix' => 'hrms/cvData', 'middleware' => 'auth', 'namespace'=>'Cv'], function(){
Route::resource('/cv','CvController');
Route::get('/cvDocument/refreshTable', 'CvDocumentController@refreshTable')->name('cvDocument.table');
Route::resource('/education', 'EducationController',['only'=>['store']]);
Route::resource('/speciality', 'SpecialityController',['only'=>['store']]);
Route::resource('/cvDocument', 'CvDocumentController');

});



Route::get('/country/states/{id?}', 'CountryController@getStates')->name('states');
Route::get('/country/cities/{id?}', 'CountryController@getCities')->name('cities');

Route::get('/home', 'HomeController@index')->name('home');
