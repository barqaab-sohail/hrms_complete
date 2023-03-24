<?php

use Illuminate\Support\Facades\Route;
use App\Models\Hr\HrEmployee;
use App\Http\Controllers\Hr\EmployeeController;


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

Route::get('/verifyCard', 'HomeController@employee');
Route::get('/verificationResult/{id?}', 'HomeController@result')->middleware('XssSanitizer')->name('verification');
Route::get('/cardVerificationResult/{employeeId?}', 'HomeController@employeeId')->middleware('XssSanitizer')->name('employee.verification');
Route::get('/assestVerificationResult/{assetCode}', 'Asset\AssetController@verification')->middleware('XssSanitizer')->name('asset.verification');

Route::get('/dashboard', 'HomeController@index')->middleware('auth')->name('dashboard');
//Route::get ('insert','Hr\EmployeeController@insert');
// Route::get('/test','HomeController@test')->middleware('auth')->name('test');
Auth::routes();

// Route::group(['prefix' => 'code', 'middleware' => ['auth','XssSanitizer'], 'namespace'=>'Auth','name' =>'opt.'], function(){
// Route::resource('/','RegisterController',['only'=>['create','store']]);
// });
Route::get('pms', 'Auth\LoginController@showLoginForm');
Route::get('pmsRegister', 'Auth\RegisterController@showRegistrationForm');
Route::get('pmsReset', 'Auth\ForgotPasswordController@showLinkRequestForm');

Route::get('code/', 'Auth\RegisterController@create')->name('opt.create');
Route::post('code/', 'Auth\RegisterController@register')->name('opt.request');
Route::post('code/', 'Auth\RegisterController@store')->name('opt.store');
// Route::prefix('hrms/')->namespace('Hr')->group(function(){
// Route::resource('/employee', 'EmployeeController');
// Route::resource('/education', 'EducationController');
// Route::resource('/appointment', 'AppointmentController');



//HR Routes
Route::group(['prefix' => 'hrms', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'Hr'], function () {

    Route::get('employee/card', 'EmployeeController@card')->name('employee.card');

    Route::post('/employeeCnic', 'EmployeeController@employeeCnic')->name('employee.cnic');
    //temporary
    Route::get('/employee/allEmployeeList', 'EmployeeController@allEmployeeList')->name('employee.allEmployeeList');

    //end temporary
    Route::get('/employee/activeEmployeesList', 'EmployeeController@activeEmployeesList')->name('employee.activeEmployeesList');

    Route::get('/employee/alertList', 'HrAlertController@alertList')->name('hrAlert.list');
    Route::get('/cnicExpiryDetail', 'HrAlertController@cnicExpiryDetail')->name('hrAlert.cnicExpiryDetail');
    Route::get('/appointmentExpiry', 'HrAlertController@appointmentExpiry')->name('hrAlert.appointmentExpiry');
    Route::get('/licenceExpiry', 'HrAlertController@drivingLicenceExpiry')->name('hrAlert.licenceExpiry');
    Route::get('/pecCardExpiry', 'HrAlertController@pecCardExpiry')->name('hrAlert.pecCardExpiry');

    Route::get('/employee/search', 'EmployeeController@search')->name('employee.search');
    Route::get('/employee/search/result', 'EmployeeController@result')->name('employee.result');


    Route::get('/employee/user/data/{id}', 'EmployeeController@userData')->name('user.data');
    Route::resource('/employee', 'EmployeeController');


    //all Employee list including terminated/resigned/retired
    //Route::get('/employee/allEmployeeList', 'EmployeeController@allEmployeeList')->name('employee.allEmployeeList');

    Route::get('/education/refreshTable', 'EducationController@refreshTable')->name('education.table');
    Route::resource('/education', 'EducationController');
    Route::get('/experience/refreshTable', 'ExperienceController@refreshTable')->name('experience.table');
    Route::resource('/experience', 'ExperienceController');
    Route::resource('/salary', 'SalaryController', ['only' => ['store']]);
    Route::resource('/appointment', 'AppointmentController', ['only' => ['edit', 'update']]);
    Route::get('/contact/refreshTable', 'ContactController@refreshTable')->name('contact.table');
    Route::resource('/contact', 'ContactController');
    Route::resource('/emergency', 'EmergencyController');
    Route::resource('/nextToKin', 'NextToKinController');

    Route::resource('/manager', 'ManagerController');
    Route::post('/employeeSalaryImport', 'EmployeeSalaryController@import')->name('employeeSalaryImport');
    Route::resource('/employeeSalary', 'EmployeeSalaryController');

    Route::get('/promotion/refreshTable', 'PromotionController@refreshTable')->name('promotion.table');
    Route::resource('/promotion', 'PromotionController');
    Route::get('/exit/refreshTable', 'ExitController@refreshTable')->name('exit.table');
    Route::resource('/exit', 'ExitController');
    Route::get('/documentation/refreshTable', 'DocumentationController@refreshTable')->name('documentation.table');
    Route::resource('/documentation', 'DocumentationController');
    Route::get('/userLogin/refreshTable', 'UserLoginController@refreshTable')->name('userLogin.table');
    Route::resource('/userLogin', 'UserLoginController', ['only' => ['edit', 'store', 'destroy']]);
    Route::resource('/picture', 'PictureController', ['only' => ['edit', 'store']]);
    Route::resource('/additionalInformation', 'AdditionalInformationController', ['only' => ['edit', 'update']]);
    Route::resource('/designation', 'DesignationController', ['only' => ['store']]);


    Route::get('/posting/refreshTable', 'PostingController@refreshTable')->name('posting.table');
    Route::resource('/posting', 'PostingController');


    Route::get('/hrReports/list', 'HrReportsController@list')->name('hrReports.list');
    Route::get('/hrReports/pictureList', 'HrReportsController@pictureList')->name('hrReports.pictureList');
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


Route::group(['prefix' => 'input', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'Input'], function () {
    Route::resource('/inputMonth', 'InputMonthController');
    Route::resource('/inputProject', 'InputProjectController');
    Route::post('/copyProject', 'InputProjectController@copy')->name('copyProject.store');
    Route::get('/input/search', 'InputController@search')->name('input.search');
    Route::get('/input/search/result', 'InputController@result')->name('input.result');
    Route::resource('/input', 'InputController');
    Route::post('/copyInput', 'InputController@copy')->name('copyInput.store');
    Route::get('/input/{id}/{month?}', 'InputController@projectList')->name('input.projectList');
    Route::get('/inputDesignation/{id}', 'InputController@projectDesignation');
});



//Self Services Routes
Route::group(['prefix' => 'hrms', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'Self'], function () {

    Route::get('/selfServices/task/{id}', 'SelfTaskController@updateStatus')->name('task.updateStatus');
    Route::resource('/selfServices/task', 'SelfTaskController');
    Route::get('/selfServices/selfContact/refreshTable', 'SelfContactController@refreshTable')->name('selfContact.table');
    Route::resource('/selfServices/selfContact', 'SelfContactController');
});

//CV Routes
Route::group(['prefix' => 'hrms/cvData', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'Cv'], function () {
    Route::get('/autocomplete/fetch', 'CvController@fetch')->name('autocomplete.fetch');
    Route::post('/cvCnic', 'CvController@cvCnic')->name('cv.cnic');
    Route::get('/search', 'CvController@search')->name('cv.search');
    Route::get('/getData/{id?}', 'CvController@getData')->name('cv.getData');
    Route::post('/result', 'CvController@result')->name('cv.result');
    Route::resource('/cv', 'CvController');
    Route::get('/cvDocument/refreshTable', 'CvDocumentController@refreshTable')->name('cvDocument.table');
    Route::post('/education', 'EducationController@store')->name('cvEducation.store');
    Route::resource('/speciality', 'SpecialityController', ['only' => ['store']]);
    Route::resource('/discipline', 'DisciplineController', ['only' => ['store']]);
    Route::resource('/cvDocument', 'CvDocumentController');
});

//Projects Routes
Route::group(['prefix' => 'hrms/project', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'Project'], function () {
    Route::post('/import', 'ProjectController@import')->name('project.import');
    Route::get('/search', 'ProjectController@search')->name('project.search');
    Route::get('/result', 'ProjectController@result')->name('project.result');
    Route::get('/selectedProjects', 'ProjectController@selectedProjects')->name('project.selected');
    Route::resource('/projectPartner', 'ProjectPartnerController');

    Route::get('/projectDocument/refreshTable', 'ProjectDocumentController@refreshTable')->name('projectDocument.table');
    Route::get('/projectDocument/reference', 'ProjectDocumentController@reference')->name('projectDocument.reference');

    Route::resource('/projectDocument', 'ProjectDocumentController');
    Route::get('/projectPosition/refreshTable', 'ProjectPositionController@refreshTable')->name('projectPosition.table');
    Route::resource('/projectPosition', 'ProjectPositionController');
    Route::get('/projectConsultancyCost/refreshTable', 'ProjectConsultancyCostController@refreshTable')->name('projectConsultancyCost.table');
    Route::resource('/projectConsultancyCost', 'ProjectConsultancyCostController');

    Route::get('/projectInvoice/chart', 'Invoice\InvoiceController@chart')->name('projectInvoice.chart');

    Route::get('/invoiceValue/{id?}', 'Payment\PaymentController@getInvoiceValue')->name('invoiceValue');
    Route::resource('/projectInvoice', 'Invoice\InvoiceController');
    Route::resource('/projectPayment', 'Payment\PaymentController');
    Route::resource('projectRights', 'ProjectRightController');
    Route::resource('projectStaff', 'ProjectStaffController');
    Route::resource('projectMonthlyExpense', 'ProjectMonthlyExpenseController');

    //Progress Routes
    Route::resource('/monthlyProgress', 'Progress\MonthlyProgressController');
    Route::resource('/projectProgress', 'Progress\ProjectProgressController');
    Route::resource('/projectProgressActivities', 'Progress\ActivitiesController');
    Route::resource('/subProject', 'SubProjectController');
    Route::resource('/projectIssues', 'Progress\ProjectIssueController');
    Route::resource('/actualVsScheduledProgress', 'Progress\ContractorProgressController');
    Route::resource('/delayReason', 'Progress\DelayReasonController');

    //Contractor Routes
    Route::resource('/projectContractor', 'Contractor\ContractorController');

    // Route::resource('/progressProgress', 'Progress\ProgressController');
    Route::get('/proejctProgressMainActivities/{level}', 'Progress\ActivitiesController@mainActivities');



    Route::get('/projectProgressChart', 'Progress\ActivityController@chart')->name('projectProgress.chart');
    Route::resource('/project', 'ProjectController');
    Route::get('/projectCode/{divisionId}', 'ProjectController@projectCode');
});

// MIS Dashboard Progress Check
Route::group(['prefix' => 'hrms/', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'MIS'], function () {
    Route::get('/MISMonitor', 'MonitorController@index')->name('MISMonitor.index');
});



//Assets Routes

Route::group(['prefix' => 'hrms', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'Asset'], function () {
    Route::post('/assetStoreClass', 'AssetController@storeClass')->name('asset.storeClass');
    Route::post('/assetStoreSubClass', 'AssetController@storeSubClass')->name('asset.storeSubClass');
    Route::get('/asset/search', 'AssetController@search')->name('asset.search');
    Route::get('/employee/asset/search/result', 'AssetController@result')->name('asset.result');
    Route::resource('/asset', 'AssetController');


    Route::resource('/asDocument', 'AssetDocumentController');
    Route::resource('/asPurchase', 'AsPurchaseController');
    Route::resource('/asLocation', 'AsLocationController');
    Route::resource('/asOwnership', 'AsOwnershipController');
    Route::resource('/asMaintenance', 'AsMaintenanceController');
    Route::resource('/asCondition', 'AsConditionController');
    Route::get('/asset/sub_classes/{id?}', 'AssetController@getSubClasses');
    Route::get('/asset/as_code/{id?}', 'AssetController@asCode');
});


//Leave Routes
Route::group(['prefix' => 'hrms', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'Leave'], function () {
    Route::resource('/leave', 'LeaveController', ['except' => ['show']]);
    Route::get('/leaveType/{id?}', 'LeaveController@leaveType')->name('leaveType');
    Route::resource('leaveStatus', 'LeaveStatusController');
    Route::resource('accumulativesLeave', 'AccumulativesLeaveController');
    Route::resource('leaveBalance', 'LeaveBalanceController');
    Route::get('/leave/search', 'LeaveController@search')->name('leave.search');
    Route::get('/leave/search/result', 'LeaveController@result')->name('leave.result');
    Route::post('/leaveImport', 'AccumulativesLeaveController@import')->name('leave.import');
});

//Submission Routes
Route::group(['prefix' => 'hrms', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'Submission'], function () {

    Route::get('/submission/eoiReference', 'SubmissionController@eoiReference');
    Route::get('/submission/submissionNo/{id}', 'SubmissionController@submissionNo');
    Route::get('/submission/search', 'SubmissionController@search')->name('submission.search');
    Route::get('/submission/search/result', 'SubmissionController@result')->name('submission.result');
    Route::resource('/submission', 'SubmissionController');
    Route::resource('/submissionPartner', 'PartnerController');
    Route::resource('/submissionDate', 'DateAndTimeController');
    Route::resource('/submissionContact', 'SubContactController');
    Route::resource('/submissionScope', 'SubScopeController');
    Route::resource('/submissionPosition', 'SubPositionController');
    Route::resource('/submissionCompetitor', 'SubCompetitorController');
    Route::resource('/submissionDocument', 'SubDocumentController');
    // Route::get('/submissionDocument/refreshTable', 'SubmissionDocumentController@refreshTable')->name('submissionDocument.table');

});

//Admin Documents Routes
Route::group(['prefix' => 'hrms', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'AdminDoc'], function () {
    Route::get('/adminDocument/reference', 'AdminDocumentController@reference')->name('adminDocument.reference');
    Route::resource('/adminDocument', 'AdminDocumentController');
});

//Misc Routes
Route::group(['prefix' => 'hrms/misc', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'Common'], function () {
    Route::resource('/office', 'OfficeController');
    Route::resource('/degree', 'DegreeController');
    Route::resource('/hrDesignation', 'DesignationController');
    Route::resource('/client', 'ClientController');
    Route::resource('/partner', 'PartnerController');
});



//Admin Routes
Route::group(['prefix' => 'hrms/admin', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'Admin'], function () {
    Route::get('/lastLogin', 'ActiveUserController@lastLogin')->name('lastLogin.detail');
    Route::get('/activeUser', 'ActiveUserController@index')->name('activeUser.index');
    Route::get('/logoutAll/{id?}', 'ActiveUserController@logoutAll')->name('logout.all');
    Route::get('/permission/employeePermission', 'PermissionController@search')->name('permission.search');
    Route::get('/permission/employeePermission/result', 'PermissionController@result')->name('permission.result');
    Route::delete('/permission/employeePermission/{id}/{userId}', 'PermissionController@employeePermissionDestroy')->name('employeePermission.destroy');
    Route::resource('/misUser', 'MisUserController');
    Route::resource('/permission', 'PermissionController');

    Route::get('/audit/search', 'AuditController@search')->name('audit.search');
    Route::get('/result', 'AuditController@result')->name('audit.result');
});

//Invoice Routes
Route::group(['prefix' => 'invoice', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'Invoice'], function () {
    Route::resource('/invoice', 'InvoiceController');
    Route::resource('/invoiceRights', 'InvoiceRightsController');
});



//General Routes
Route::group(['middleware' => ['auth', 'XssSanitizer']], function () {
    Route::get('/country/states/{id?}', 'CountryController@getStates')->name('states');
    Route::get('/country/cities/{id?}', 'CountryController@getCities')->name('cities');
    Route::post('/client', 'Submission\SubmissionController@addClient')->name('addClient');
    Route::post('/partner', 'Submission\SubmissionController@addPartner')->name('addPartner');
});

//Route::get('/home', 'HomeController@index')->name('home');
