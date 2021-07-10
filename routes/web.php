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
//Route::get ('insert','Hr\EmployeeController@insert');

Auth::routes();


Route::prefix('code')->namespace('Auth')->name('otp.')->group(function(){
Route::resource('/','RegisterController',['only'=>['create','store']]);
});


// Route::prefix('hrms/')->namespace('Hr')->group(function(){
// Route::resource('/employee', 'EmployeeController');
// Route::resource('/education', 'EducationController');
// Route::resource('/appointment', 'AppointmentController');



//HR Routes
Route::group(['prefix' => 'hrms', 'middleware' => ['auth','XssSanitizer'], 'namespace'=>'Hr'], function(){
Route::post('/employeeCnic','EmployeeController@employeeCnic')->name('employee.cnic');
//temporary
Route::get('/employee/allEmployeeList', 'EmployeeController@allEmployeeList')->name('employee.allEmployeeList');

//end temporary 
Route::get('/employee/activeEmployeesList', 'EmployeeController@activeEmployeesList')->name('employee.activeEmployeesList');
Route::resource('/employee', 'EmployeeController');

//all Employee list including terminated/resigned/retired
//Route::get('/employee/allEmployeeList', 'EmployeeController@allEmployeeList')->name('employee.allEmployeeList');

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
Route::resource('/manager', 'ManagerController');
Route::get('/promotion/refreshTable', 'PromotionController@refreshTable')->name('promotion.table');
Route::resource('/promotion', 'PromotionController');
Route::get('/exit/refreshTable', 'ExitController@refreshTable')->name('exit.table');
Route::resource('/exit', 'ExitController');
Route::get('/documentation/refreshTable', 'DocumentationController@refreshTable')->name('documentation.table');
Route::resource('/documentation', 'DocumentationController');
Route::get('/userLogin/refreshTable', 'UserLoginController@refreshTable')->name('userLogin.table');
Route::resource('/userLogin', 'UserLoginController',['only'=>['edit','store','destroy']]);
Route::resource('/picture', 'PictureController',['only'=>['edit','store']]);
Route::resource('/additionalInformation', 'AdditionalInformationController',['only'=>['edit','update']]);
Route::resource('/designation', 'DesignationController',['only'=>['store']]);


Route::get('/posting/refreshTable', 'PostingController@refreshTable')->name('posting.table');
Route::resource('/posting', 'PostingController');


Route::get('/hrReports/list', 'HrReportsController@list')->name('hrReports.list');
Route::get('/hrReports/cnicExpiryList', 'HrReportsController@cnicExpiryList')->name('hrReports.cnicExpiryList');
Route::get('/hrReports/missingDocumentList', 'HrReportsController@missingDocumentList')->name('hrReports.missingDocumentList');
Route::get('/hrReports/searchEmployee', 'HrReportsController@searchEmployee')->name('hrReports.searchEmployee');
Route::get('/hrReports/report_1', 'HrReportsController@report_1')->name('hrReports.report_1');
Route::post('/hrReports/searchEmployeeResult', 'HrReportsController@searchEmployeeResult')->name('hrReports.searchEmployeeResult');

//Route::resource('/hrMonthlyReport', 'HrMonthlyReportController');
//Route::get('/hrMonthlyReportProject/refreshTable', 'HrMonthlyReportProjectController@refreshTable')->name('hrMonthlyProject.table');
//Route::resource('/hrMonthlyReportProject', 'HrMonthlyReportProjectController');
Route::get('/charts/category', 'HrChartController@category')->name('charts.category');

});


Route::group(['prefix' => 'input', 'middleware' => ['auth','XssSanitizer'], 'namespace'=>'input'], function(){
	Route::resource('/inputMonth', 'InputMonthController');
	Route::resource('/inputProject', 'InputProjectController');
	Route::resource('/input', 'InputController');

	Route::get('/input/{id}/{month?}', 'InputController@projectList')->name('input.projectList');

});



//Self Services Routes
Route::group(['prefix' => 'hrms', 'middleware' => ['auth','XssSanitizer'], 'namespace'=>'Self'], function(){

	Route::get('/selfServices/task/{id}','SelfTaskController@updateStatus')->name('task.updateStatus');
	Route::resource('/selfServices/task','SelfTaskController');
	Route::get('/selfServices/selfContact/refreshTable', 'SelfContactController@refreshTable')->name('selfContact.table');
	Route::resource('/selfServices/selfContact','SelfContactController');
});

//CV Routes
Route::group(['prefix' => 'hrms/cvData', 'middleware' => ['auth','XssSanitizer'], 'namespace'=>'Cv'], function(){
Route::get('/autocomplete/fetch', 'CvController@fetch')->name('autocomplete.fetch');
Route::post('/cvCnic','CvController@cvCnic')->name('cv.cnic');
Route::get('/search','CvController@search')->name('cv.search');
Route::get('/getData/{id?}','CvController@getData')->name('cv.getData');
Route::post('/result','CvController@result')->name('cv.result');
Route::resource('/cv','CvController');
Route::get('/cvDocument/refreshTable', 'CvDocumentController@refreshTable')->name('cvDocument.table');
Route::post('/education', 'EducationController@store')->name('cvEducation.store');
Route::resource('/speciality', 'SpecialityController',['only'=>['store']]);
Route::resource('/discipline', 'DisciplineController',['only'=>['store']]);
Route::resource('/cvDocument', 'CvDocumentController');

});

//Projects Routes
Route::group(['prefix' => 'hrms', 'middleware' => ['auth','XssSanitizer'], 'namespace'=>'Project'], function(){
Route::post('/project/import', 'ProjectController@import')->name('project.import');
Route::resource('/project', 'ProjectController');
Route::resource('/projectPartner', 'ProjectPartnerController');

Route::get('/projectDocument/refreshTable', 'ProjectDocumentController@refreshTable')->name('projectDocument.table');
Route::get('/projectDocument/reference', 'ProjectDocumentController@reference')->name('projectDocument.reference');

Route::resource('/projectDocument', 'ProjectDocumentController');
Route::get('/projectPosition/refreshTable', 'ProjectPositionController@refreshTable')->name('projectPosition.table');
Route::resource('/projectPosition', 'ProjectPositionController');
Route::get('/projectConsultancyCost/refreshTable', 'ProjectConsultancyCostController@refreshTable')->name('projectConsultancyCost.table');
Route::resource('/projectConsultancyCost', 'ProjectConsultancyCostController');

Route::resource('/projectCode', 'ProjectCodeController');

});

//Assets Routes

Route::group(['prefix' => 'hrms', 'middleware' => ['auth','XssSanitizer'], 'namespace'=>'Asset'], function(){
Route::post('/assetStoreClass','AssetController@storeClass')->name('asset.storeClass');
Route::resource('/asset','AssetController');
Route::resource('/asDocument','AssetDocumentController');
Route::resource('/asPurchase','AsPurchaseController');
Route::resource('/asLocation','AsLocationController');
Route::resource('/asOwnership','AsOwnershipController');
Route::get('/asset/sub_classes/{id?}', 'AssetController@getSubClasses');
Route::get('/asset/as_code/{id?}', 'AssetController@asCode');


});



//Submission Routes
Route::group(['prefix' => 'hrms', 'middleware' => ['auth','XssSanitizer'], 'namespace'=>'submission'], function(){
	Route::resource('/submission', 'SubmissionController');
	Route::resource('/submissionDocument', 'SubmissionDocumentController');
	Route::get('/submissionDocument/refreshTable', 'SubmissionDocumentController@refreshTable')->name('submissionDocument.table');

});

//Admin Routes
Route::group(['prefix' => 'hrms/admin', 'middleware' => ['auth','XssSanitizer'], 'namespace'=>'Admin'], function(){
	Route::get('/lastLogin', 'ActiveUserController@lastLogin')->name('lastLogin.detail');
	Route::get('/activeUser', 'ActiveUserController@index')->name('activeUser.index');
	Route::get('/logoutAll/{id?}', 'ActiveUserController@logoutAll')->name('logout.all');
	Route::resource('/permission', 'PermissionController');

});

//Invoice Routes
Route::group(['prefix' => 'invoice', 'middleware' => ['auth','XssSanitizer'], 'namespace'=>'Invoice'], function(){
	Route::resource('/invoice', 'InvoiceController');
	Route::resource('/invoiceRights', 'InvoiceRightsController');

});






//General Routes
Route::group(['middleware' => ['auth','XssSanitizer']], function(){
Route::get('/country/states/{id?}', 'CountryController@getStates')->name('states');
Route::get('/country/cities/{id?}', 'CountryController@getCities')->name('cities');

});

//Route::get('/home', 'HomeController@index')->name('home');
