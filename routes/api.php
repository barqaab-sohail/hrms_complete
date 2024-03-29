<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\User;
use App\Models\MisUser;

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


Route::post('/user', 'Mobile\EmployeeController@user');
Route::post('/user/login', 'Android\Auth\UserController@login');
Route::post('/mis/login', 'MIS\LoginController@login');
Route::get('/proejctSummaryMM/{id}', 'MIS\Project\ProjectController@proejctSummaryMM');
Route::get('/manMonthProjectsStatus', 'MIS\Project\ProjectController@manMonthProjectsStatus');



// DashBoard / MIS API
Route::group(['middleware' => ['auth:sanctum']], function () {
    //Projects Routes
    Route::post('/mis/logout', 'MIS\LoginController@logout');
    Route::get('/invoiceData', 'Dashboard\DashboardController@invoiceData');
    Route::get('/projectDetail/{id}', 'Dashboard\DashboardController@projectDetail');
    Route::get('/projectExpenseChart/{id}', 'Dashboard\DashboardController@projectExpenseChart');
    Route::get('/powerRunningProjectsTable', 'Dashboard\DashboardController@powerRunningProjectsTable');
    Route::get('/currentMonthPaymentReceived', 'Dashboard\DashboardController@currentMonthPaymentReceived');
    Route::get('/lastMonthPaymentReceived', 'Dashboard\DashboardController@lastMonthPaymentReceived');
    Route::get('/currentMonthInvoices', 'Dashboard\DashboardController@currentMonthInvoices');
    Route::get('/lastMonthInvoices', 'Dashboard\DashboardController@lastMonthInvoices');
    Route::get('/totalBudgetExpenditure/{id}', 'Dashboard\DashboardController@totalBudgetExpenditure');



    //HR Routes
    Route::get('/employees', 'MIS\Hr\EmployeeController@index');
    Route::get('/employeeDocuments/{id}', 'MIS\Hr\EmployeeDocumentController@show');


    //Assets Routes
    Route::get('/assets', 'MIS\Asset\AssetController@index');

    //Projects Routes
    Route::get('/projectDocuments/{id}', 'MIS\Project\ProjectController@projectDocuments');
    Route::get('/allProjectDocuments', 'MIS\Project\ProjectController@allProjectDocuments');
});







Route::group(['middleware' => ['auth:sanctum']], function () {
    //Route::get('/user/employee', 'Mobile\EmployeeController@index');
    Route::post('/user/logout', 'Android\Auth\UserController@logout');
    Route::get('/ageChart', 'Android\Hr\EmployeeController@ageChart');
    Route::get('/hr/employees', 'Android\Hr\EmployeeController@employees');
    Route::get('/hr/employee/documents/{id}', 'Android\Hr\EmployeeController@documents');


    //Asset API
    Route::get('/asset/employees', 'Android\Asset\AssetController@employees');
    Route::get('/asset/offices', 'Android\Asset\AssetController@offices');
    Route::get('/asset/classes', 'Android\Asset\AssetController@classes');
    Route::get('/clients', 'Android\Asset\AssetController@clients');
    Route::get('/asset/subClasses/{id}', 'Android\Asset\AssetController@subClasses');
    Route::post('/asset/store', 'Android\Asset\AssetController@store');
    Route::get('/asset/asset/{id}', 'Android\Asset\AssetController@show');
});
