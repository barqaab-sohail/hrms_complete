<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrAllowanceName;
use DataTables;
use DB;

class AllowanceNameController extends Controller
{
	public function index(Request $request)
	{

		return view('hr.allowanceName.list');
	}

	public function loadData(Request $request)
	{
		if ($request->ajax()) {
			$data = HrAllowanceName::all();
			return DataTables::of($data)
				->addColumn('Edit', function ($data) {
					$button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Delete" class="btn btn-success btn-sm editAllowanceName">Edit</a>';

					return $button;
				})
				->addColumn('Delete', function ($data) {

					$button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteAllowanceName">Delete</a>';
					return $button;
				})
				->rawColumns(['Edit', 'Delete'])
				->make(true);
		}
	}

	public function store(Request $request)
	{

		$validated = $request->validate([
			'name' => 'required|unique:hr_allowance_names|max:255',
		]);


		$input = $request->all();

		DB::transaction(function () use ($input) {

			HrAllowanceName::updateOrCreate(['id' => $input['allowance_name_id']], $input);
		}); // end transcation

		return response()->json(['status' => 'OK', 'message' => "Data Successfully Saved"]);
	}

	public function edit($id)
	{
		$data = HrAllowanceName::find($id);
		return response()->json($data);
	}

	public function destroy($id)
	{
		DB::transaction(function () use ($id) {
			HrAllowanceName::findOrFail($id)->delete();
		}); // end transcation

		return response()->json(['success' => 'data  delete successfully.']);
	}
}
