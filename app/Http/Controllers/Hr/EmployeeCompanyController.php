<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrEmployeeCompany;
use App\Models\Common\Partner;
use DB;
use DataTables;

class EmployeeCompanyController extends Controller
{
    public function show($id)
    {
        $partners = Partner::all();
        $employeeCompanies = HrEmployeeCompany::where('hr_employee_id', $id)->get();
        $view =  view('hr.company.create', compact('employeeCompanies', 'partners', 'id'))->render();
        return response()->json($view);
    }

    public function create(Request $request)
    {

        if ($request->ajax()) {
            $data = HrEmployeeCompany::where('hr_employee_id', $request->hrEmployeeId)->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editCompany">Edit</a>';

                    return $btn;
                })
                ->addColumn('Delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteCompany">Delete</a>';


                    return $btn;
                })
                ->editColumn('effective_date', function ($row) {

                    return \Carbon\Carbon::parse($row->effective_date)->format('M d, Y');
                })
                ->editColumn('end_date', function ($row) {

                    if ($row->end_date) {
                        return \Carbon\Carbon::parse($row->end_date)->format('M d, Y');
                    } else {
                        return '';
                    }
                })
                ->editColumn('hr_employee_id', function ($row) {

                    return $row->hrEmployee->full_name;
                })
                ->editColumn('partner_id', function ($row) {

                    return $row->partner->name;
                })
                ->rawColumns(['Edit', 'Delete'])
                ->make(true);
        }

        $employeeCompanies = HrEmployeeCompany::where('hr_employee_id',  $request->hrEmployeeId)->get();
        $view =  view('hr.company.create', compact('employeeCompanies'))->render();
        return response()->json($view);
    }

    public function store(Request $request)
    {

        $input = $request->all();

        if ($request->filled('effective_date')) {
            $input['effective_date'] = \Carbon\Carbon::parse($request->effective_date)->format('Y-m-d');
        }
        if ($request->filled('end_date')) {
            $input['end_date'] = \Carbon\Carbon::parse($request->end_date)->format('Y-m-d');
        }

        DB::transaction(function () use ($input) {

            HrEmployeeCompany::updateOrCreate(
                ['id' => $input['employee_company_id']],
                [
                    'effective_date' => $input['effective_date'],
                    'partner_id' => $input['partner_id'],
                    'end_date' => $input['end_date'],
                    'status' => $input['status'],
                    'hr_employee_id' => $input['hr_employee_id']
                ]
            );
        }); // end transcation      

        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $data = HrEmployeeCompany::find($id);

        return response()->json($data);
    }

    public function destroy(Request $request, $id)
    {
        $employeeContract = HrEmployeeCompany::find($id);

        DB::transaction(function () use ($id) {
            HrEmployeeCompany::find($id)->delete();
        }); // end transcation 

        return response()->json(['success' => 'Contract  delete successfully.']);
    }
}
