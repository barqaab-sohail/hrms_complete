<?php

use App\Models\Hr\HrEmployee;
use App\Models\Hr\EmployeeManager;
use App\Models\Hr\EmployeeDepartment;
use App\Charts\Hr\DepartmentChart;
use App\Models\Common\Office;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


function ageChart()
{
	return Cache::remember('age_chart_data', now()->addHours(6), function () {

		$countBelowForty = HrEmployee::where('date_of_birth', '>=', \Carbon\Carbon::now()->subYears(40))->where('hr_status_id', 1)->count();

		$countBelowFifty = HrEmployee::where('date_of_birth', '>=', \Carbon\Carbon::now()->subYears(50))->where('hr_status_id', 1)->count() - $countBelowForty;

		$countBelowSixty = HrEmployee::where('date_of_birth', '>=', \Carbon\Carbon::now()->subYears(60))->where('hr_status_id', 1)->count() - $countBelowForty - $countBelowFifty;

		$countBelowSeventy = HrEmployee::where('date_of_birth', '>=', \Carbon\Carbon::now()->subYears(70))->where('hr_status_id', 1)->count() - $countBelowForty - $countBelowFifty - $countBelowSixty;

		$countAboveSeventy = HrEmployee::where('hr_status_id', 1)->count() - $countBelowForty - $countBelowFifty - $countBelowSixty - $countBelowSeventy;

		return ['countBelowForty' => $countBelowForty, 'countBelowFifty' => $countBelowFifty, 'countBelowSixty' => $countBelowSixty, 'countBelowSeventy' => $countBelowSeventy, 'countAboveSeventy' => $countAboveSeventy];
	});
}


function educationChart()
{
	return Cache::remember('education_chart_data', now()->addHours(6), function () {
		$array20 =  DB::table('hr_employees')
			->join('hr_educations', 'hr_employees.id', '=', 'hr_educations.hr_employee_id')
			->join('educations', 'educations.id', '=', 'hr_educations.education_id')
			->where('educations.level', '>=', 20)->select('hr_employees.employee_no')->distinct('hr_employees.employee_no')->where('hr_employees.hr_status_id', 1)->pluck('employee_no')->toArray();

		$above20 =  sizeof($array20);

		// $above18 =  DB::table('hr_employees')
		// 	->join('hr_educations', 'hr_employees.id', '=', 'hr_educations.hr_employee_id')
		// 	->join('educations', 'educations.id', '=', 'hr_educations.education_id')
		// 	->where('educations.level', '>=', 18)->select('hr_employees.*')->distinct('hr_employees.employee_no')->where('hr_employees.hr_status_id', 1)->whereNotIn('hr_employees.employee_no', $array20)->count();

		$array18 =  DB::table('hr_employees')
			->join('hr_educations', 'hr_employees.id', '=', 'hr_educations.hr_employee_id')
			->join('educations', 'educations.id', '=', 'hr_educations.education_id')
			->where('educations.level', '>=', 18)->select('hr_employees.*')->distinct('hr_employees.employee_no')->where('hr_employees.hr_status_id', 1)->whereNotIn('hr_employees.employee_no', $array20)->pluck('employee_no')->toArray();

		$above18 =  sizeof($array18);

		$array18 = array_merge($array18, $array20);

		// $above16 =  DB::table('hr_employees')
		// 	->join('hr_educations', 'hr_employees.id', '=', 'hr_educations.hr_employee_id')
		// 	->join('educations', 'educations.id', '=', 'hr_educations.education_id')
		// 	->where('educations.level', '>=', 16)->select('hr_employees.*')->distinct('hr_employees.employee_no')->where('hr_employees.hr_status_id', 1)->whereNotIn('hr_employees.employee_no', $array18)->count();

		$array16 =  DB::table('hr_employees')
			->join('hr_educations', 'hr_employees.id', '=', 'hr_educations.hr_employee_id')
			->join('educations', 'educations.id', '=', 'hr_educations.education_id')
			->where('educations.level', '>=', 16)->select('hr_employees.*')->distinct('hr_employees.employee_no')->where('hr_employees.hr_status_id', 1)->whereNotIn('hr_employees.employee_no', $array18)->pluck('employee_no')->toArray();

		$above16 =  sizeof($array16);

		$array16 = array_merge($array18, $array16);

		// $above14 =  DB::table('hr_employees')
		// 	->join('hr_educations', 'hr_employees.id', '=', 'hr_educations.hr_employee_id')
		// 	->join('educations', 'educations.id', '=', 'hr_educations.education_id')
		// 	->where('educations.level', '>=', 14)->select('hr_employees.*')->distinct('hr_employees.employee_no')->where('hr_employees.hr_status_id', 1)->whereNotIn('hr_employees.employee_no', $array16)->count();

		$array14 =  DB::table('hr_employees')
			->join('hr_educations', 'hr_employees.id', '=', 'hr_educations.hr_employee_id')
			->join('educations', 'educations.id', '=', 'hr_educations.education_id')
			->where('educations.level', '>=', 14)->select('hr_employees.*')->distinct('hr_employees.employee_no')->where('hr_employees.hr_status_id', 1)->whereNotIn('hr_employees.employee_no', $array16)->pluck('employee_no')->toArray();

		$above14 =  sizeof($array14);
		$array14 = array_merge($array14, $array16);

		// $above12 =  DB::table('hr_employees')
		// 	->join('hr_educations', 'hr_employees.id', '=', 'hr_educations.hr_employee_id')
		// 	->join('educations', 'educations.id', '=', 'hr_educations.education_id')
		// 	->where('educations.level', '>=', 12)->select('hr_employees.*')->distinct('hr_employees.employee_no')->where('hr_employees.hr_status_id', 1)->whereNotIn('hr_employees.employee_no', $array14)->count();

		$array12 =  DB::table('hr_employees')
			->join('hr_educations', 'hr_employees.id', '=', 'hr_educations.hr_employee_id')
			->join('educations', 'educations.id', '=', 'hr_educations.education_id')
			->where('educations.level', '>=', 12)->select('hr_employees.*')->distinct('hr_employees.employee_no')->where('hr_employees.hr_status_id', 1)->whereNotIn('hr_employees.employee_no', $array14)->pluck('employee_no')->toArray();

		$above12 =  sizeof($array12);
		$array12 = array_merge($array14, $array12);

		$above10 =  DB::table('hr_employees')
			->join('hr_educations', 'hr_employees.id', '=', 'hr_educations.hr_employee_id')
			->join('educations', 'educations.id', '=', 'hr_educations.education_id')
			->where('educations.level', '>=', 10)->select('hr_employees.*')->distinct('hr_employees.employee_no')->where('hr_employees.hr_status_id', 1)->whereNotIn('hr_employees.employee_no', $array12)->count();


		return ['10Years' => $above10, '12Years' => $above12, '14Years' => $above14, '16Years' => $above16, '18Years' => $above18, '20Years' => $above20];
	});
	// return $hremployees = HrEmployee::with('degreeAbove16')->get();
}

function categoryChart()
{
	return Cache::remember('category_chart_data', now()->addHours(6), function () {
		$categoryA = HrEmployee::join('employee_appointments', 'employee_appointments.hr_employee_id', '=', 'hr_employees.id')->select('hr_employees.*', 'employee_appointments.hr_category_id')->where('hr_category_id', 1)->where('hr_status_id', 1)->count();
		$categoryB = HrEmployee::join('employee_appointments', 'employee_appointments.hr_employee_id', '=', 'hr_employees.id')->select('hr_employees.*', 'employee_appointments.hr_category_id')->where('hr_category_id', 2)->where('hr_status_id', 1)->count();
		$categoryC = HrEmployee::join('employee_appointments', 'employee_appointments.hr_employee_id', '=', 'hr_employees.id')->select('hr_employees.*', 'employee_appointments.hr_category_id')->where('hr_category_id', 3)->where('hr_status_id', 1)->count();

		return ['categoryA' => $categoryA, 'categoryB' => $categoryB, 'categoryC' => $categoryC];
	});
}

function engineerChart()
{
	return Cache::remember('engineer_chart_data', now()->addHours(6), function () {
		$pecRegisteredEngineers = HrEmployee::join('hr_memberships', 'hr_memberships.hr_employee_id', '=', 'hr_employees.id')->select('hr_employees.*', 'hr_memberships.membership_no')->where('hr_status_id', 1)->count();
		$associatedEngineers = HrEmployee::join('hr_educations', 'hr_educations.hr_employee_id', '=', 'hr_employees.id')->select('hr_employees.*', 'hr_educations.education_id')->where('hr_status_id', 1)->whereIn('education_id', [32, 33, 46, 134])->count();
		$allEmployees = HrEmployee::where('hr_status_id', 1)->count();

		return ['pecRegisteredEngineers' => $pecRegisteredEngineers, 'associatedEngineers' => $associatedEngineers, 'allEmployees' => $allEmployees];
	});
}


function departmentChart()
{
	return Cache::remember('department_chart_data', now()->addHours(6), function () {
		$result = collect(HrEmployee::join('employee_departments', 'employee_departments.hr_employee_id', '=', 'hr_employees.id')->select('hr_employees.*', 'employee_departments.hr_department_id', 'employee_departments.effective_date')->where('hr_status_id', 1)->orderBy('effective_date', 'desc')->get());
		$resultUnique = ($result->unique('id'));
		$resultUnique->values()->all();
		$finance = $resultUnique->where('hr_department_id', 1)->count();
		$power = $resultUnique->where('hr_department_id', 2)->count();
		$water = $resultUnique->where('hr_department_id', 3)->count();

		return ['finance' => $finance, 'power' => $power, 'water' => $water];
	});
}
