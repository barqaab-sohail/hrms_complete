<?php

use App\User;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrExperience;
use App\Livewire\Asset\ListAsset;
use App\Livewire\Asset\CreateAsset;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hr\HrReportsController;
use App\Http\Controllers\Hr\BankLetterController;
use App\Http\Controllers\Common\ShortUrlController;
use App\Http\Controllers\Email\EmailAddressController;
use App\Http\Controllers\Hr\ExperienceLetterController;
use App\Http\Controllers\Project\DocumentSearchController;
use App\Http\Controllers\Project\ProjectLedgerActivityController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| testing
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/newDesign', function () {

//     $hrEmployee = HrEmployee::find(20);
//     $user = User::where('id', $hrEmployee->user_id)->first();
//     $data = $user->getAllPermissions();
//     return  $data;
// });







Route::get('/employeeAllowances/{id}', 'Hr\EmployeeSalaryController@getEmployeeAllowanceName');
Route::get('/verifyCard', 'HomeController@employee');
Route::get('/verificationResult/{id?}', 'HomeController@result')->middleware('XssSanitizer')->name('verification');
Route::get('/cardVerificationResult/{employeeId?}', 'HomeController@employeeId')->middleware('XssSanitizer')->name('employee.verification');
Route::get('/assestVerificationResult/{assetCode}', 'Asset\AssetController@verification')->middleware('XssSanitizer')->name('asset.verification');

Route::get('/dashboard', 'HomeController@index')->middleware('auth')->name('dashboard');
//Route::get ('insert','Hr\EmployeeController@insert');
Route::get('/test', 'Hr\EmployeeController@getEmployees');
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

    Route::get('/upload', 'StaffStatusController@showUploadForm')->name('checking.data');
    Route::post('/process-files', 'StaffStatusController@processFiles')->name('process.files');
    Route::get('employee/card', 'CardController@create')->name('employee.card');
    Route::post('employee/card', 'CardController@index')->name('employeeCard.index');
    Route::get('employee/getEmployeePicture/{id}', 'CardController@getEmployeePicture')->name('employee.getEmployeePicture');

    Route::post('/employeeCnic', 'EmployeeController@employeeCnic')->name('employee.cnic');
    //temporary
    Route::get('/employee/allEmployeeList', 'EmployeeController@allEmployeeList')->name('employee.allEmployeeList');


    //end temporary
    // Check Employee Asset Status
    Route::get('/employee/employee_asset_status', 'EmployeeController@employeeAssetStatus')->name('employee.asset.status');
    Route::get('/employee/employee_asset_result', 'EmployeeController@employeeAssetResult')->name('employee.asset.result');
    // End Check Employee Asset Status

    Route::get('/employee/activeEmployeesList', 'EmployeeController@activeEmployeesList')->name('employee.activeEmployeesList');

    Route::get('/employee/alertList', 'HrAlertController@alertList')->name('hrAlert.list');
    Route::get('/cnicExpiryDetail', 'HrAlertController@cnicExpiryDetail')->name('hrAlert.cnicExpiryDetail');
    Route::get('/appointmentExpiry', 'HrAlertController@appointmentExpiry')->name('hrAlert.appointmentExpiry');
    Route::get('/licenceExpiry', 'HrAlertController@drivingLicenceExpiry')->name('hrAlert.licenceExpiry');
    Route::get('/pecCardExpiry', 'HrAlertController@pecCardExpiry')->name('hrAlert.pecCardExpiry');
    Route::get('/leaveStaffActiveStatus', 'HrAlertController@leaveStaffActiveStatus')->name('hrAlert.leaveStaffActiveStatus');

    Route::get('/employee/search', 'EmployeeController@search')->name('employee.search');
    Route::get('/employee/search/result', 'EmployeeController@result')->name('employee.result');


    Route::get('/employee/user/data/{id}', 'EmployeeController@userData')->name('user.data');
    Route::resource('/employee', 'EmployeeController')->except(['show']);
    Route::get('/employee/loaddata', 'EmployeeController@loadData')->name('employee.loadData');
    Route::get('/employees/refresh', 'EmployeeController@refresh')->name('employees.refresh');


    //all Employee list including terminated/resigned/retired
    //Route::get('/employee/allEmployeeList', 'EmployeeController@allEmployeeList')->name('employee.allEmployeeList');

    Route::get('/education/refreshTable', 'EducationController@refreshTable')->name('education.table');
    Route::resource('/education', 'EducationController');
    Route::get('/experience/refreshTable', 'ExperienceController@refreshTable')->name('experience.table');
    Route::resource('/experience', 'ExperienceController');
    Route::resource('/salary', 'SalaryController', ['only' => ['store']]);
    Route::resource('/appointment', 'AppointmentController', ['only' => ['edit', 'update']]);
    Route::get('/getAppointmentData', 'AppointmentController@getData');
    Route::get('/contact/refreshTable/{id?}', 'ContactController@refreshTable')->name('contact.table');
    Route::resource('/contact', 'ContactController');
    Route::resource('/emergency', 'EmergencyController');
    Route::resource('/nextToKin', 'NextToKinController');

    Route::resource('/manager', 'ManagerController');

    Route::resource('/employeeOffice', 'EmployeeOfficeController');
    Route::post('/employeeSalaryImport', 'EmployeeSalaryController@import')->name('employeeSalaryImport');
    Route::resource('/employeeSalary', 'EmployeeSalaryController');
    Route::resource('/employeeContract', 'EmployeeContractController');
    Route::resource('/employeeCompany', 'EmployeeCompanyController');

    Route::get('/promotion/refreshTable', 'PromotionController@refreshTable')->name('promotion.table');
    Route::resource('/promotion', 'PromotionController');
    Route::get('/exit/refreshTable', 'ExitController@refreshTable')->name('exit.table');
    Route::resource('/exit', 'ExitController');
    Route::get('/documentation/refreshTable', 'DocumentationController@refreshTable')->name('documentation.table');
    Route::resource('/documentation', 'DocumentationController');
    Route::get('/userLogin/refreshTable', 'UserLoginController@refreshTable')->name('userLogin.table');
    Route::resource('/userLogin', 'UserLoginController', ['only' => ['show', 'store', 'destroy', 'create']]);
    Route::resource('/picture', 'PictureController', ['only' => ['edit', 'store']]);
    Route::resource('/additionalInformation', 'AdditionalInformationController', ['only' => ['edit', 'update']]);
    Route::resource('/designation', 'DesignationController', ['only' => ['store']]);


    Route::get('/posting/refreshTable', 'PostingController@refreshTable')->name('posting.table');
    Route::resource('/posting', 'PostingController');


    Route::get('/hrReports/list', 'HrReportsController@index')->name('hrReports.list');
    Route::get('/hrReports/create', 'HrReportsController@create')->name('hrReports.create');
    Route::get('/hrReports/pictureList', 'HrReportsController@pictureList')->name('hrReports.pictureList');
    Route::get('/hrReports/cnicExpiryList', 'HrReportsController@cnicExpiryList')->name('hrReports.cnicExpiryList');
    Route::get('/hrReports/shortprofile', 'HrReportsController@shortProfile')->name('hrReports.shortprofile');
    Route::get('/hrReports/missingDocumentList', 'HrReportsController@missingDocumentList')->name('hrReports.missingDocumentList');
    // New Missing Documents Route
    Route::get('/hrReports/newmissingdocuments', 'HrReportsController@missingDocumentsView')->name('newmissingdocuments');
    Route::get('/hrReports/newmissingdocuments/data', 'HrReportsController@missingDocumentsTable')->name('hrReports.newmissingdocuments');

    // End New Missing Documents Route
    Route::get('/hrReports/missingDocuments', 'HrReportsController@mmissingDocuments')->name('missingDocuments.list');
    Route::get('/hrReports/searchEmployee', 'HrReportsController@searchEmployee')->name('hrReports.searchEmployee');
    Route::get('/hrReports/report_1', 'HrReportsController@report_1')->name('hrReports.report_1');
    Route::get('/hrReports/employee_list', 'HrReportsController@employee_list')->name('hr.reports.employee_list');
    Route::post('/hrReports/searchEmployeeResult', 'HrReportsController@searchEmployeeResult')->name('hrReports.searchEmployeeResult');
    Route::resource('/employeeBank', 'BankController');
    Route::get('/allBankAccounts', 'BankController@allBankAccounts')->name('allBankAccounts.list');
    Route::get('/allBankAccounts/loaddata', 'BankController@loadData')->name('allBankAccounts.loadData');
    Route::get('/importBankDetail', 'BankController@view')->name('importBankDetail.view');
    Route::post('/importBankDetail', 'BankController@import')->name('importBankDetail.import');
    Route::prefix('hr/reports')->name('hr.reports.')->group(function () {
        Route::get('/', [HrReportsController::class, 'index'])->name('index');
        Route::post('/', [HrReportsController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [HrReportsController::class, 'edit'])->name('edit');
        Route::put('/{id}', [HrReportsController::class, 'store'])->name('update');
        Route::delete('/{id}', [HrReportsController::class, 'destroy'])->name('destroy');
    });

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
    Route::get('/multiplePrints', 'MultiplePrintsController@print')->name('multiplePrint.print');
    Route::post('/multiplePrints', 'MultiplePrintsController@output')->name('multiplePrint.output');
    Route::resource('/personaldocuments', 'PersonalDocumentsController')->except(['show']);
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

    Route::Resource('/mmUtilization', 'Invoice\MmUtilizationController');
    Route::get('/exportView/{prDetailId}', 'Invoice\MmUtilizationController@exportView')->name('project.utilization');

    Route::get('/invoice/{id}', 'Invoice\MmUtilizationController@invoice');
    Route::Resource('/prDirectCostUtilization', 'Invoice\PrDirectCostUtilizationController');
    Route::get('/exportViewDirectCost/{prDetailId}', 'Invoice\PrDirectCostUtilizationController@exportViewDirectCost')->name('project.exportViewDirectCost');

    Route::post('/import', 'ProjectController@import')->name('project.import');
    Route::get('/search', 'ProjectController@search')->name('project.search');
    Route::get('/result', 'ProjectController@result')->name('project.result');
    Route::get('/selectedProjects', 'ProjectController@selectedProjects')->name('project.selected');
    Route::resource('/projectPartner', 'ProjectPartnerController');

    Route::get('/projectDocument/refreshTable', 'ProjectDocumentController@refreshTable')->name('projectDocument.table');
    Route::get('/projectDocument/projectFolder/{folderId}/{prDetailId}', 'ProjectDocumentController@showFolder')->name('projectDocument.showFolder');
    Route::get('/projectDocument/reference', 'ProjectDocumentController@reference')->name('projectDocument.reference');

    Route::resource('/projectDocument', 'ProjectDocumentController');
    Route::get('/projectPosition/refreshTable', 'ProjectPositionController@refreshTable')->name('projectPosition.table');
    Route::resource('/projectPosition', 'ProjectPositionController');
    Route::resource('/directCostDetail', 'DirectCostDetailController');
    Route::get('/projectConsultancyCost/refreshTable', 'ProjectConsultancyCostController@refreshTable')->name('projectConsultancyCost.table');
    Route::resource('/projectConsultancyCost', 'ProjectConsultancyCostController');

    Route::get('/projectInvoice/chart', 'Invoice\InvoiceController@chart')->name('projectInvoice.chart');

    Route::get('/invoiceValue/{id?}', 'Payment\PaymentController@getInvoiceValue')->name('invoiceValue');
    Route::resource('/projectInvoice', 'Invoice\InvoiceController');
    Route::resource('/projectPayment', 'Payment\PaymentController');
    Route::resource('projectRights', 'ProjectRightController');
    Route::resource('projectCustomerNo', 'ProjectCustomerNoController');
    Route::resource('projectLedgerActivity', 'ProjectLedgerActivityController');
    Route::post('/importLedgerActivity/{prDetailId}', 'ProjectLedgerActivityController@importLedgerActivity')->name('project.importLedgerActivity');
    Route::resource('projectStaff', 'ProjectStaffController');
    Route::resource('projectMonthlyExpense', 'ProjectMonthlyExpenseController');
    Route::post('/importExpense/{prDetailId}', 'ProjectMonthlyExpenseController@importExpense')->name('project.importExpense');

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
    Route::get('/proejctProgressMainActivities/{level}/{prDetailId}', 'Progress\ActivitiesController@mainActivities');



    Route::get('/projectProgressChart', 'Progress\ActivityController@chart')->name('projectProgress.chart');
    Route::resource('/project', 'ProjectController');
    Route::get('/projectCode/{divisionId}', 'ProjectController@projectCode');

    // Search Documents

    Route::get('/documents', [DocumentSearchController::class, 'index'])->name('documents.index');
    Route::get('/documents/download/{id}', [DocumentSearchController::class, 'download'])->name('documents.download');
});

// MIS Dashboard Progress Check
Route::group(['prefix' => 'hrms/', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'MIS'], function () {
    Route::get('/MISMonitor', 'MonitorController@index')->name('MISMonitor.index');
    Route::get('/create', 'MonitorController@create')->name('MISMonitor.create');
});



//Assets Routes

Route::group(['prefix' => 'hrms', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'Asset'], function () {
    Route::post('/assetStoreClass', 'AssetController@storeClass')->name('asset.storeClass');
    Route::post('/assetStoreSubClass', 'AssetController@storeSubClass')->name('asset.storeSubClass');
    Route::get('/asset/search', 'AssetController@search')->name('asset.search');
    Route::get('/employee/asset/search/result', 'AssetController@result')->name('asset.result');
    Route::resource('/asset', 'AssetController')->except(['show']);
    Route::get('/asset/loaddata', 'AssetController@loadData')->name('asset.loadData');

    Route::resource('/asDocument', 'AssetDocumentController');
    Route::resource('/asPurchase', 'AsPurchaseController');
    Route::resource('/asLocation', 'AsLocationController');
    Route::resource('/asOwnership', 'AsOwnershipController');
    Route::resource('/asMaintenance', 'AsMaintenanceController');
    Route::resource('/asConsumable', 'AsConsumableController');
    Route::resource('/asCondition', 'AsConditionController');
    Route::get('/asset/sub_classes/{id?}', 'AssetController@getSubClasses');
    Route::get('/asset/as_code/{id?}', 'AssetController@asCode');
});



//Route::get('/asset_livewire', ListAsset::class);

//Photocopy Routes

Route::group(['middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'Photocopy'], function () {
    Route::resource('/photocopy', 'PhotocopyController');
    Route::get('/photocopy_list', 'PhotocopyRecordController@list')->name('photocopy.list');
    Route::resource('/photocopy_record', 'PhotocopyRecordController');
    Route::resource('/photocopy_documents', 'PhotocopyDocumentsController');
});

//Folders Routes

Route::group(['middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'Folder'], function () {
    Route::resource('/folder', 'FolderController');
    Route::get('/folder_list', 'FolderController@list')->name('folder.list');
    Route::resource('/folder_documents', 'FolderDocumentsController');
});

//Leave Routes
Route::get('/onlineLeave', 'Leave\LeaveController@onlineLeave');
Route::post('/leaveEmployeeData', 'Leave\LeaveController@employeeData')->name('leave.employeeData');
Route::group(['prefix' => 'hrms', 'middleware' => ['XssSanitizer'], 'namespace' => 'Leave'], function () {
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
    Route::get('/loaddata', 'SubmissionController@loadData')->name('submission.loadData');
    Route::resource('/submissionPartner', 'PartnerController');
    Route::resource('/submissionDate', 'DateAndTimeController');
    Route::resource('/submissionContact', 'SubContactController');
    Route::resource('/submissionScope', 'SubScopeController');
    Route::resource('/submissionPosition', 'SubPositionController');
    Route::resource('/submissionCompetitor', 'SubCompetitorController');
    Route::resource('/submissionDocument', 'SubDocumentController');
    Route::resource('/securities', 'SecurityController');
    Route::get('/securities/export/excel', 'SecurityController@exportExcel')->name('securities.export.excel');
    Route::get('/securities/export/pdf', 'SecurityController@exportPdf')->name('securities.export.pdf');
    // Route::get('/submissionDocument/refreshTable', 'SubmissionDocumentController@refreshTable')->name('submissionDocument.table');

});

//Admin Documents Routes
Route::group(['prefix' => 'hrms', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'AdminDoc'], function () {
    Route::get('/adminDocument/reference', 'AdminDocumentController@reference')->name('adminDocument.reference');
    Route::resource('/adminDocument', 'AdminDocumentController');
});

//Misc Routes
Route::group(['prefix' => 'hrms/misc', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'Common'], function () {
    Route::resource('/office', 'OfficeController')->except(['show']);
    Route::get('/office/loaddata', 'OfficeController@loadData')->name('office.loadData');
    Route::resource('/degree', 'DegreeController')->except(['show']);
    Route::get('/degree/loaddata', 'DegreeController@loadData')->name('degree.loadData');
    Route::resource('/hrDesignation', 'DesignationController')->except(['show']);
    Route::get('/hrDesignation/loaddata', 'DesignationController@loaddata')->name('hrDesignation.loadData');
    Route::resource('/client', 'ClientController')->except(['show']);
    Route::get('/client/loaddata', 'ClientController@loadData')->name('client.loadData');
    Route::resource('/partner', 'PartnerController')->except(['show']);
    Route::get('/partner/loaddata', 'PartnerController@loadData')->name('partner.loadData');
    Route::resource('/allowanceName', 'AllowanceNameController')->except(['show']);
    Route::get('/allowanceName/loaddata', 'AllowanceNameController@loadData')->name('allowanceName.loadData');
    Route::resource('/directCostDescription', 'DirectCostDescriptionController')->except(['show']);
    Route::get('/directCostDescription/loaddata', 'DirectCostDescriptionController@loadData')->name('directCostDescription.loadData');
    Route::get('/createShortUrl', [ShortUrlController::class, 'create'])->name('shorten.create');
    Route::post('/shorten', [ShortUrlController::class, 'store'])->name('shorten.store');
});

Route::get('document/{code}', [ShortUrlController::class, 'redirect']);

// Bank Account Opening Letter Routes
Route::prefix('bank-letters')->group(function () {
    Route::get('/', [BankLetterController::class, 'create'])->name('bank-letters.create');
    Route::post('/preview', [BankLetterController::class, 'preview'])->name('bank-letters.preview');
    Route::post('/generate', [BankLetterController::class, 'generate'])->name('bank-letters.generate');
    Route::get('bank-letters/list', [BankLetterController::class, 'list'])->name('bank-letters.list');
});

// Experience Letter Routes
Route::prefix('hr')->group(function () {
    // Experience Letters
    Route::get('experience-letters/create', [ExperienceLetterController::class, 'create'])->name('experience-letters.create');
    Route::post('experience-letters/preview', [ExperienceLetterController::class, 'preview'])->name('experience-letters.preview');
    Route::post('experience-letters/generate', [ExperienceLetterController::class, 'generate'])->name('experience-letters.generate');
    Route::post('experience-letters/generate-without-letterhead', [ExperienceLetterController::class, 'generateWithoutLetterhead'])
        ->name('experience-letters.generate-without-letterhead');
    Route::get('experience-letters/list', [ExperienceLetterController::class, 'list'])->name('experience-letters.list');
});

//Admin Routes
Route::group(['prefix' => 'hrms/admin', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'Admin'], function () {
    Route::post('/updateLedgerActivity', [ProjectLedgerActivityController::class, 'updateLedgerActivity'])->name('project.updateLedgerActivity');
    Route::get('/lastLogin', 'ActiveUserController@lastLogin')->name('lastLogin.detail');
    Route::get('/lastLogin/create', 'ActiveUserController@create')->name('lastLogin.create');
    Route::get('/activeUser', 'ActiveUserController@index')->name('activeUser.index');
    Route::get('/logoutAll/{id?}', 'ActiveUserController@logoutAll')->name('logout.all');
    Route::get('/permission/employeePermission', 'PermissionController@search')->name('permission.search');
    Route::get('/permission/employeePermission/result', 'PermissionController@result')->name('permission.result');
    Route::delete('/permission/employeePermission/{id}/{userId}', 'PermissionController@employeePermissionDestroy')->name('employeePermission.destroy');
    Route::resource('/misUser', 'MisUserController');
    Route::get('/permission/userList', 'PermissionController@userList')->name('permission.userList');
    Route::post('/permission/addPermission', 'PermissionController@addPermission')->name('permission.add');
    Route::delete('/permission/userAllPermissionDelete', 'PermissionController@userAllPermissionDelete')->name('permission.userAllPermissionDelete');
    Route::delete('/permission/userPermissionDestroy/{permissionName}/{userId}', 'PermissionController@userPermissionDestroy')->name('userPermission.destroy');
    Route::resource('/permission', 'PermissionController');

    Route::get('/audit/search', 'AuditController@search')->name('audit.search');
    Route::get('/result', 'AuditController@result')->name('audit.result');
    Route::resource('/addUser', 'UserController');
    Route::resource('/tempfileupload', 'TempFileUploadController');
});

//Invoice Routes
Route::group(['prefix' => 'invoice', 'middleware' => ['auth', 'XssSanitizer'], 'namespace' => 'Invoice'], function () {
    Route::resource('/invoice', 'InvoiceController');
    Route::resource('/invoiceRights', 'InvoiceRightsController');
});

// Email Address Routes
Route::group(['prefix' => 'emails', 'as' => 'emails.'], function () {
    Route::get('/type/{type}', [EmailAddressController::class, 'getTypeaheadData']);
    Route::get('/', [EmailAddressController::class, 'index'])->name('index');
    Route::get('/create', [EmailAddressController::class, 'create'])->name('create');
    Route::post('/', [EmailAddressController::class, 'store'])->name('store');
    Route::get('/{email}/edit', [EmailAddressController::class, 'edit'])->name('edit');
    Route::put('/{email}', [EmailAddressController::class, 'update'])->name('update');
    Route::delete('/{email}', [EmailAddressController::class, 'destroy'])->name('destroy');
});

//General Routes
Route::group(['middleware' => ['auth', 'XssSanitizer']], function () {
    Route::get('/country/states/{id?}', 'CountryController@getStates')->name('states');
    Route::get('/country/cities/{id?}', 'CountryController@getCities')->name('cities');
    Route::post('/client', 'Submission\SubmissionController@addClient')->name('addClient');
    Route::post('/partner', 'Submission\SubmissionController@addPartner')->name('addPartner');
});

//Route::get('/home', 'HomeController@index')->name('home');