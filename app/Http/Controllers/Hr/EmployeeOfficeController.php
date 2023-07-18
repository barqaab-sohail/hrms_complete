<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Office;
use App\Models\Hr\EmployeeOffice;
use DB;
use DataTables;

class EmployeeOfficeController extends Controller
{
    public function index()
    {

        $offices = Office::all();

        $view =  view('hr.office.create', compact('offices'))->render();
        return response()->json($view);
    }

    public function create(Request $request)
    {

        if ($request->ajax()) {
            $data = EmployeeOffice::where('hr_employee_id', session('hr_employee_id'))->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editEmployeeOffice">Edit</a>';

                    return $btn;
                })
                ->addColumn('Delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteEmployeeOffice">Delete</a>';


                    return $btn;
                })
                ->editColumn('office_id', function ($row) {

                    return $row->office->name ?? '';
                })
                ->rawColumns(['Edit', 'Delete'])
                ->make(true);
        }

        $offices = Office::all();

        $view =  view('hr.office.create', compact('offices'))->render();
        return response()->json($view);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'office_id' => ['required'],
            'effective_date' => ['required']
        ]);

        $input = $request->all();
        if ($request->filled('effective_date')) {
            $input['effective_date'] = \Carbon\Carbon::parse($request->effective_date)->format('Y-m-d');
        }

        DB::transaction(function () use ($input) {


            EmployeeOffice::updateOrCreate(
                ['id' => $input['employee_office_id']],
                $input
            );
        }); // end transcation      

        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $employeeOffice = EmployeeOffice::find($id);

        return response()->json($employeeOffice);
    }

    public function destroy($id)
    {

        $employeeOffice = EmployeeOffice::where('hr_employee_id', session('hr_employee_id'))->first();

        if ($employeeOffice->id == $id) {
            return response()->json(['error' => 'data  not deleted. data update from Appointment']);
        }

        DB::transaction(function () use ($id) {
            EmployeeOffice::find($id)->delete();
        }); // end transcation 
        return response()->json(['success' => 'data  delete successfully.']);
    }
}
