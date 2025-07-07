<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\EmployeeContract;
use App\Models\Hr\HrEmployee;
use App\Http\Requests\Hr\EmployeeContractStore;
use DB;
use DataTables;

class EmployeeContractController extends Controller
{
    public function show($id)
    {
        $employeeContracts = EmployeeContract::where('hr_employee_id', $id)->orderBy('to', 'desc')->get();
        $view =  view('hr.contract.create', compact('employeeContracts', 'id'))->render();
        return response()->json($view);
    }

    public function create(Request $request)
    {

        if ($request->ajax()) {
            $data = EmployeeContract::where('hr_employee_id', $request->hrEmployeeId)->orderBy('to', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editContract">Edit</a>';

                    return $btn;
                })
                ->addColumn('Delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteContract">Delete</a>';


                    return $btn;
                })
                ->editColumn('from', function ($row) {

                    return \Carbon\Carbon::parse($row->from)->format('M d, Y');
                })
                ->editColumn('to', function ($row) {

                    return \Carbon\Carbon::parse($row->to)->format('M d, Y');
                })
                ->editColumn('hr_employee_id', function ($row) {

                    return $row->hrEmployee->full_name;
                })
                ->rawColumns(['Edit', 'Delete'])
                ->make(true);
        }

        $employeeContracts = EmployeeContract::where('hr_employee_id',  $request->hrEmployeeId)->get();

        $view =  view('hr.contract.create', compact('employeeContracts'))->render();
        return response()->json($view);
    }

    public function store(EmployeeContractStore $request)
    {

        $input = $request->all();

        DB::transaction(function () use ($input) {

            EmployeeContract::updateOrCreate(
                ['id' => $input['employee_contract_id']],
                [
                    'from' => $input['from'],
                    'to' => $input['to'],
                    'hr_employee_id' => $input['hr_employee_id']
                ]
            );

            $employee = HrEmployee::find($input['hr_employee_id']);
            $currentExpireDate = $employee->employeeAppointment->expiry_date ?? '';
            if (empty($currentExpireDate) || $input['to'] > $currentExpireDate) {

                DB::table('employee_appointments')
                    ->where('hr_employee_id', $input['hr_employee_id'])
                    ->update(['expiry_date' => $input['to']]);
            }
        }); // end transcation      

        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $data = EmployeeContract::find($id);

        return response()->json($data);
    }

    public function destroy(Request $request, $id)
    {
        $employeeContract = EmployeeContract::find($id);

        DB::transaction(function () use ($id) {
            EmployeeContract::find($id)->delete();
        }); // end transcation 

        //after delete update Employee Appointment Expiry Date
        $data = EmployeeContract::where('hr_employee_id', $request->hrEmployeeId)->orderBy('to', 'desc')->first();
        if ($data && $data->to <= $employeeContract->to) {
            DB::table('employee_appointments')
                ->where('hr_employee_id', $request->hrEmployeeId)
                ->update(['expiry_date' => $data->to]);
        } else if (empty($data)) {
            DB::table('employee_appointments')
                ->where('hr_employee_id', $request->hrEmployeeId)
                ->update(['expiry_date' => null]);
        }

        return response()->json(['success' => 'Contract  delete successfully.']);
    }
}
