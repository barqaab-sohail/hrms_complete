<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Common\OfficeStore;
use App\Models\Common\Country;
use App\Models\Hr\HrEmployee;
use App\Models\Common\Office;
use App\Models\Common\OfficePhone;
use DB;
use DataTables;

class OfficeController extends Controller
{

	public function index(Request $request)
	{

		if ($request->ajax()) {
			$data = Office::orderBy('id', 'desc')->get();
			return DataTables::of($data)
				->addColumn('hr_employee_id', function ($data) {
					if ($data->officePhones) {
						$employees = '';
						foreach ($data->officePhones as $employee) {
							$hrEmployee = HrEmployee::find($employee->hr_employee_id);
							$employees .= $hrEmployee->full_name ?? '';
							$employees .= '<br>';
						}
						return $employees;
					} else {
						return '';
					}
				})
				->addColumn('phone_no', function ($data) {
					if ($data->officePhones) {
						$phones = '';
						foreach ($data->officePhones as $phone) {
							$phones .= $phone->phone_no ?? '';
							$phones .= '<br>';
						}
						return $phones;
					} else {
						return '';
					}
				})
				->addColumn('is_active', function ($data) {
					$status = '';
					$color = '';

					if ($data->is_active == 0) {
						$status = 'Closed';
						$color = 'btn-danger';
					} else {
						$status = 'Working';
						$color = 'btn-success';
					}

					return '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit" class="edit btn ' . $color . '  btn-sm editStatus">' . $status . '</a>';
				})
				->addColumn('edit', function ($data) {

					$button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Delete" class="btn btn-success btn-sm editOffice">Edit</a>';

					return $button;
				})
				->addColumn('delete', function ($data) {

					$button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteOffice">Delete</a>';
					return $button;
				})

				->rawColumns(['edit', 'delete', 'phone_no', 'hr_employee_id', 'is_active'])
				->make(true);
		}
		$employees = HrEmployee::all(['id', 'first_name', 'last_name', 'employee_no']);
		return view('common.office.list', compact('employees'));
	}

	public function create()
	{
		$countries = Country::all(['id', 'name']);
		return response()->json(["countries" => $countries]);
	}

	public function store(OfficeStore $request)
	{

		$input = $request->all();

		DB::transaction(function () use ($input, $request) {

			$office = Office::updateOrCreate(['id' => $input['office_id']], $input);

			if ($request->filled("phone_no.0")) {

				$officePhoneIds = OfficePhone::where('office_id', $office->id)->get()->pluck('id')->toArray();
				$requestOfficePhoneIds = $request->input("office_phone_id");
				$officePhones = OfficePhone::where('office_id', $office->id)->get();

				for ($i = 0; $i < count($request->input('phone_no')); $i++) {
					$officePhone['office_id'] = $office->id;
					$officePhone['phone_no'] = $request->input("phone_no.$i");
					$officePhone['hr_employee_id'] = $request->input("hr_employee_id.$i");
					OfficePhone::updateOrCreate(['id' => $request->input("office_phone_id.$i")], $officePhone);
				}

				foreach ($officePhones as $officePhone) {
					if (!in_array($officePhone->id, $requestOfficePhoneIds)) {
						OfficePhone::findOrFail($officePhone->id)->delete();
					}
				}
			} else {
				$officePhones = OfficePhone::where('office_id', $input['office_id'])->get();
				if ($officePhones) {
					foreach ($officePhones as $officePhone) {
						OfficePhone::findOrFail($officePhone->id)->delete();
					}
				}
			}
		}); // end transcation

		return response()->json(['status' => 'OK', 'message' => "Submission Successfully Saved"]);
	}

	public function edit($id)
	{
		$data = Office::with('officePhones')->find($id);
		return response()->json($data);
	}

	public function destroy($id)
	{
		DB::transaction(function () use ($id) {
			Office::findOrFail($id)->delete();
		}); // end transcation

		return response()->json(['success' => 'data  delete successfully.']);
	}
}
