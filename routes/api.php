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


Route::Post('/user','Mobile\EmployeeController@user');
Route::Post('/user/login','Android\Auth\UserController@login');


Route::group(['middleware' => ['auth:sanctum']], function () {
	
	Route::get('/user/employee', 'Mobile\EmployeeController@index');
    Route::post('/user/logout', 'Android\Auth\UserController@logout');

    Route::get('/asset/classes','Android\Asset\AssetController@classes');
	Route::get('/clients','Android\Asset\AssetController@clients');
	Route::get('/asset/subClasses/{id}','Android\Asset\AssetController@subClasses');
	Route::get('/asset/asset/{id}','Android\Asset\AssetController@show');
	Route::post('/asset/store','Android\Asset\AssetController@store');

   

});