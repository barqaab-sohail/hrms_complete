<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/user','Mobile\EmployeeController@user');
Route::post('/user/login','Android\Auth\UserController@login');




Route::group(['middleware' => ['auth:sanctum']], function () {
	//Route::get('/user/employee', 'Mobile\EmployeeController@index');
	Route::post('/user/logout', 'Android\Auth\UserController@logout');
   	Route::get('/ageChart','Android\Hr\EmployeeController@ageChart');
   	Route::get('/hr/employees','Android\Hr\EmployeeController@employees');
	Route::get('/hr/employee/documents/{id}','Android\Hr\EmployeeController@documents');

//Asset API
   	Route::get('/asset/employees','Android\Asset\AssetController@employees');
	Route::get('/asset/offices','Android\Asset\AssetController@offices');
	Route::get('/asset/classes','Android\Asset\AssetController@classes');
	Route::get('/clients','Android\Asset\AssetController@clients');
	Route::get('/asset/subClasses/{id}','Android\Asset\AssetController@subClasses');
	Route::post('/asset/store','Android\Asset\AssetController@store');
	Route::get('/asset/asset/{id}','Android\Asset\AssetController@show');
	

});