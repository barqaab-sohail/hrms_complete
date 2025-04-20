<?php

use App\Models\Hr\HrEmployee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;


// function ageChart()
// {
// 	return Cache::remember('age_chart_data', now()->addHours(6), function () {

// 		$countBelowForty = HrEmployee::where('date_of_birth', '>=', \Carbon\Carbon::now()->subYears(40))->where('hr_status_id', 1)->count();

// 		$countBelowFifty = HrEmployee::where('date_of_birth', '>=', \Carbon\Carbon::now()->subYears(50))->where('hr_status_id', 1)->count() - $countBelowForty;

// 		$countBelowSixty = HrEmployee::where('date_of_birth', '>=', \Carbon\Carbon::now()->subYears(60))->where('hr_status_id', 1)->count() - $countBelowForty - $countBelowFifty;

// 		$countBelowSeventy = HrEmployee::where('date_of_birth', '>=', \Carbon\Carbon::now()->subYears(70))->where('hr_status_id', 1)->count() - $countBelowForty - $countBelowFifty - $countBelowSixty;

// 		$countAboveSeventy = HrEmployee::where('hr_status_id', 1)->count() - $countBelowForty - $countBelowFifty - $countBelowSixty - $countBelowSeventy;

// 		return ['countBelowForty' => $countBelowForty, 'countBelowFifty' => $countBelowFifty, 'countBelowSixty' => $countBelowSixty, 'countBelowSeventy' => $countBelowSeventy, 'countAboveSeventy' => $countAboveSeventy];
// 	});
// }

function ageChart()
{
	return Cache::remember('age_chart_data', now()->addHours(6), function () {
		$today = Carbon::today();

		// Fetch birthdates of all active employees
		$employees = HrEmployee::where('hr_status_id', 1)
			->select('date_of_birth')
			->get();

		// Count employees in each age group
		$buckets = [
			'countBelowForty' => fn($age) => $age < 40,
			'countBelowFifty' => fn($age) => $age >= 40 && $age < 50,
			'countBelowSixty' => fn($age) => $age >= 50 && $age < 60,
			'countBelowSeventy' => fn($age) => $age >= 60 && $age < 70,
			'countAboveSeventy' => fn($age) => $age >= 70,
		];

		$result = array_fill_keys(array_keys($buckets), 0);

		foreach ($employees as $emp) {
			$age = Carbon::parse($emp->date_of_birth)->age;

			foreach ($buckets as $label => $check) {
				if ($check($age)) {
					$result[$label]++;
					break;
				}
			}
		}

		return $result;
	});
}


// function educationChart()
// {
// 	return Cache::remember('education_chart_data', now()->addHours(6), function () {
// 		$array20 =  DB::table('hr_employees')
// 			->join('hr_educations', 'hr_employees.id', '=', 'hr_educations.hr_employee_id')
// 			->join('educations', 'educations.id', '=', 'hr_educations.education_id')
// 			->where('educations.level', '>=', 20)->select('hr_employees.employee_no')->distinct('hr_employees.employee_no')->where('hr_employees.hr_status_id', 1)->pluck('employee_no')->toArray();

// 		$above20 =  sizeof($array20);

// 		$array18 =  DB::table('hr_employees')
// 			->join('hr_educations', 'hr_employees.id', '=', 'hr_educations.hr_employee_id')
// 			->join('educations', 'educations.id', '=', 'hr_educations.education_id')
// 			->where('educations.level', '>=', 18)->select('hr_employees.*')->distinct('hr_employees.employee_no')->where('hr_employees.hr_status_id', 1)->whereNotIn('hr_employees.employee_no', $array20)->pluck('employee_no')->toArray();

// 		$above18 =  sizeof($array18);

// 		$array18 = array_merge($array18, $array20);


// 		$array16 =  DB::table('hr_employees')
// 			->join('hr_educations', 'hr_employees.id', '=', 'hr_educations.hr_employee_id')
// 			->join('educations', 'educations.id', '=', 'hr_educations.education_id')
// 			->where('educations.level', '>=', 16)->select('hr_employees.*')->distinct('hr_employees.employee_no')->where('hr_employees.hr_status_id', 1)->whereNotIn('hr_employees.employee_no', $array18)->pluck('employee_no')->toArray();

// 		$above16 =  sizeof($array16);

// 		$array16 = array_merge($array18, $array16);


// 		$array14 =  DB::table('hr_employees')
// 			->join('hr_educations', 'hr_employees.id', '=', 'hr_educations.hr_employee_id')
// 			->join('educations', 'educations.id', '=', 'hr_educations.education_id')
// 			->where('educations.level', '>=', 14)->select('hr_employees.*')->distinct('hr_employees.employee_no')->where('hr_employees.hr_status_id', 1)->whereNotIn('hr_employees.employee_no', $array16)->pluck('employee_no')->toArray();

// 		$above14 =  sizeof($array14);
// 		$array14 = array_merge($array14, $array16);


// 		$array12 =  DB::table('hr_employees')
// 			->join('hr_educations', 'hr_employees.id', '=', 'hr_educations.hr_employee_id')
// 			->join('educations', 'educations.id', '=', 'hr_educations.education_id')
// 			->where('educations.level', '>=', 12)->select('hr_employees.*')->distinct('hr_employees.employee_no')->where('hr_employees.hr_status_id', 1)->whereNotIn('hr_employees.employee_no', $array14)->pluck('employee_no')->toArray();

// 		$above12 =  sizeof($array12);
// 		$array12 = array_merge($array14, $array12);

// 		$above10 =  DB::table('hr_employees')
// 			->join('hr_educations', 'hr_employees.id', '=', 'hr_educations.hr_employee_id')
// 			->join('educations', 'educations.id', '=', 'hr_educations.education_id')
// 			->where('educations.level', '>=', 10)->select('hr_employees.*')->distinct('hr_employees.employee_no')->where('hr_employees.hr_status_id', 1)->whereNotIn('hr_employees.employee_no', $array12)->count();


// 		return ['10Years' => $above10, '12Years' => $above12, '14Years' => $above14, '16Years' => $above16, '18Years' => $above18, '20Years' => $above20];
// 	});
// }

// New function
function educationChart()
{
	return Cache::remember('education_chart_data', now()->addHours(6), function () {
		// Get highest education level for each active employee
		$employees = DB::table('hr_employees')
			->join('hr_educations', 'hr_employees.id', '=', 'hr_educations.hr_employee_id')
			->join('educations', 'educations.id', '=', 'hr_educations.education_id')
			->where('hr_employees.hr_status_id', 1)
			->select('hr_employees.employee_no', DB::raw('MAX(educations.level) as max_level'))
			->groupBy('hr_employees.employee_no')
			->get();

		// Buckets
		$buckets = [
			'20Years' => 20,
			'18Years' => 18,
			'16Years' => 16,
			'14Years' => 14,
			'12Years' => 12,
			'10Years' => 10,
		];

		$result = [];

		foreach ($buckets as $label => $level) {
			$count = $employees->filter(fn($emp) => $emp->max_level >= $level)->count();
			$alreadyCounted = array_sum($result);
			$result[$label] = $count - $alreadyCounted;
		}

		return $result;
	});
}

// function categoryChart()
// {
// 	return Cache::remember('category_chart_data', now()->addHours(6), function () {
// 		$categoryA = HrEmployee::join('employee_appointments', 'employee_appointments.hr_employee_id', '=', 'hr_employees.id')->select('hr_employees.*', 'employee_appointments.hr_category_id')->where('hr_category_id', 1)->where('hr_status_id', 1)->count();
// 		$categoryB = HrEmployee::join('employee_appointments', 'employee_appointments.hr_employee_id', '=', 'hr_employees.id')->select('hr_employees.*', 'employee_appointments.hr_category_id')->where('hr_category_id', 2)->where('hr_status_id', 1)->count();
// 		$categoryC = HrEmployee::join('employee_appointments', 'employee_appointments.hr_employee_id', '=', 'hr_employees.id')->select('hr_employees.*', 'employee_appointments.hr_category_id')->where('hr_category_id', 3)->where('hr_status_id', 1)->count();

// 		return ['categoryA' => $categoryA, 'categoryB' => $categoryB, 'categoryC' => $categoryC];
// 	});
// }

function categoryChart()
{
	return Cache::remember('category_chart_data', now()->addHours(6), function () {
		// Get counts for each category in a single query
		$categories = DB::table('hr_employees')
			->join('employee_appointments', 'employee_appointments.hr_employee_id', '=', 'hr_employees.id')
			->where('hr_employees.hr_status_id', 1)
			->select('employee_appointments.hr_category_id', DB::raw('COUNT(*) as total'))
			->groupBy('employee_appointments.hr_category_id')
			->pluck('total', 'hr_category_id');

		return [
			'categoryA' => $categories->get(1, 0),
			'categoryB' => $categories->get(2, 0),
			'categoryC' => $categories->get(3, 0),
		];
	});
}

// function engineerChart()
// {
// 	return Cache::remember('engineer_chart_data', now()->addHours(6), function () {
// 		$pecRegisteredEngineers = HrEmployee::join('hr_memberships', 'hr_memberships.hr_employee_id', '=', 'hr_employees.id')->select('hr_employees.*', 'hr_memberships.membership_no')->where('hr_status_id', 1)->count();
// 		$associatedEngineers = HrEmployee::join('hr_educations', 'hr_educations.hr_employee_id', '=', 'hr_employees.id')->select('hr_employees.*', 'hr_educations.education_id')->where('hr_status_id', 1)->whereIn('education_id', [32, 33, 46, 134])->count();
// 		$allEmployees = HrEmployee::where('hr_status_id', 1)->count();

// 		return ['pecRegisteredEngineers' => $pecRegisteredEngineers, 'associatedEngineers' => $associatedEngineers, 'allEmployees' => $allEmployees];
// 	});
// }

function engineerChart()
{
	return Cache::remember('engineer_chart_data', now()->addHours(6), function () {
		// Base filter
		$activeEmployees = HrEmployee::where('hr_status_id', 1);

		// Count all active employees once
		$allEmployees = (clone $activeEmployees)->count();

		// PEC registered engineers: those who have any membership record
		$pecRegisteredEngineers = DB::table('hr_employees')
			->join('hr_memberships', 'hr_memberships.hr_employee_id', '=', 'hr_employees.id')
			->where('hr_employees.hr_status_id', 1)
			->distinct('hr_employees.id')
			->count('hr_employees.id');

		// Associated engineers: based on specific education IDs
		$associatedEngineers = DB::table('hr_employees')
			->join('hr_educations', 'hr_educations.hr_employee_id', '=', 'hr_employees.id')
			->where('hr_employees.hr_status_id', 1)
			->whereIn('hr_educations.education_id', [32, 33, 46, 134])
			->distinct('hr_employees.id')
			->count('hr_employees.id');

		return [
			'pecRegisteredEngineers' => $pecRegisteredEngineers,
			'associatedEngineers' => $associatedEngineers,
			'allEmployees' => $allEmployees,
		];
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

// function departmentChart()
// {
// 	return Cache::remember('department_chart_data', now()->addHours(6), function () {
// 		// Subquery to get latest department assignment per employee
// 		$latestDepartments = DB::table('employee_departments as ed1')
// 			->select('ed1.hr_employee_id', 'ed1.hr_department_id')
// 			->join(DB::raw('(
//                 SELECT hr_employee_id, MAX(effective_date) as max_date
//                 FROM employee_departments
//                 GROUP BY hr_employee_id
//             ) as ed2'), function ($join) {
// 				$join->on('ed1.hr_employee_id', '=', 'ed2.hr_employee_id')
// 					->on('ed1.effective_date', '=', 'ed2.max_date');
// 			});

// 		// Join with employees
// 		$departments = DB::table('hr_employees')
// 			->joinSub($latestDepartments, 'latest', function ($join) {
// 				$join->on('hr_employees.id', '=', 'latest.hr_employee_id');
// 			})
// 			->where('hr_employees.hr_status_id', 1)
// 			->select('latest.hr_department_id', DB::raw('COUNT(*) as total'))
// 			->groupBy('latest.hr_department_id')
// 			->pluck('total', 'hr_department_id');

// 		return [
// 			'finance' => $departments->get(1, 0),
// 			'power'   => $departments->get(2, 0),
// 			'water'   => $departments->get(3, 0),
// 		];
// 	});
// }
