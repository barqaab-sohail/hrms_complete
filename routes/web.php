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


Route::get('/dashboard','HomeController@index');
Route::get('/hrms/testing','HomeController@testing');

Auth::routes();

Route::prefix('code')->namespace('Auth')->name('otp.')->group(function(){
Route::resource('/','RegisterController',['only'=>['create','store']]);
});


// Route::prefix('hrms/')->namespace('Hr')->group(function(){
// Route::resource('/employee', 'EmployeeController');
// Route::resource('/education', 'EducationController');
// Route::resource('/appointment', 'AppointmentController');

// });


//HR Routes
Route::group(['prefix' => 'hrms', 'middleware' => 'auth', 'namespace'=>'Hr'], function(){
Route::post('/employeeCnic','EmployeeController@employeeCnic')->name('employee.cnic');
Route::resource('/employee', 'EmployeeController');
Route::get('/education/refreshTable', 'EducationController@refreshTable')->name('education.table');
Route::resource('/education', 'EducationController');
Route::get('/experience/refreshTable', 'ExperienceController@refreshTable')->name('experience.table');
Route::resource('/experience', 'ExperienceController');
Route::resource('/salary', 'SalaryController',['only'=>['store']]);
Route::resource('/appointment', 'AppointmentController',['only'=>['edit','update']]);
Route::get('/contact/refreshTable', 'ContactController@refreshTable')->name('contact.table');
Route::resource('/contact', 'ContactController');
Route::resource('/emergency', 'EmergencyController');
Route::resource('/nextToKin', 'NextToKinController');
Route::resource('/promotion', 'PromotionController');
Route::get('/documentation/refreshTable', 'DocumentationController@refreshTable')->name('documentation.table');
Route::resource('/documentation', 'DocumentationController');
Route::get('/userLogin/refreshTable', 'UserLoginController@refreshTable')->name('userLogin.table');
Route::resource('/userLogin', 'UserLoginController',['only'=>['edit','store','destroy']]);
Route::resource('/picture', 'PictureController',['only'=>['edit','store']]);

});

//CV Routes

Route::group(['prefix' => 'hrms/cvData', 'middleware' => 'auth', 'namespace'=>'Cv'], function(){
Route::get('/autocomplete/fetch', 'CvController@fetch')->name('autocomplete.fetch');
Route::post('/cvCnic','CvController@cvCnic')->name('cv.cnic');
Route::get('/search','CvController@search')->name('cv.search');
Route::post('/result','CvController@result')->name('cv.result');
Route::resource('/cv','CvController');
Route::get('/cvDocument/refreshTable', 'CvDocumentController@refreshTable')->name('cvDocument.table');
Route::post('/education', 'EducationController@store')->name('cvEducation.store');
Route::resource('/speciality', 'SpecialityController',['only'=>['store']]);
Route::resource('/cvDocument', 'CvDocumentController');

});

//Projects Routes
Route::group(['prefix' => 'hrms', 'middleware' => 'auth', 'namespace'=>'Project'], function(){
Route::resource('/project', 'ProjectController');


});




//Admin Routes
Route::group(['prefix' => 'hrms/admin', 'middleware' => 'auth', 'namespace'=>'Admin'], function(){
	Route::get('/lastLogin', 'ActiveUserController@lastLogin')->name('lastLogin.detail');
	Route::get('/activeUser', 'ActiveUserController@index')->name('activeUser.index');
	Route::get('/logoutAll/{id?}', 'ActiveUserController@logoutAll')->name('logout.all');
	Route::resource('/permission', 'PermissionController');

});






//General Routes
Route::group(['middleware' => 'auth'], function(){
Route::get('/country/states/{id?}', 'CountryController@getStates')->name('states');
Route::get('/country/cities/{id?}', 'CountryController@getCities')->name('cities');
});

//Route::get('/home', 'HomeController@index')->name('home');
