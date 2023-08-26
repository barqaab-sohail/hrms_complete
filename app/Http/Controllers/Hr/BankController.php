<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Bank;
use App\Imports\Hr\BankDetailImport;
use App\Models\Hr\EmployeeBank;
use Illuminate\Validation\Rule;
use App\Models\Hr\HrEmployee;
use DB;
use DataTables;


class BankController extends Controller
{



    public function allBankAccounts(Request $request)
    {

        if ($request->ajax()) {
            $data = EmployeeBank::with('hrEmployee', 'bank', 'hrEmployee.employeeCurrentDesignation')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editEmployeeBank">Edit</a>';

                    return $btn;
                })
                ->addColumn('Delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteEmployeeBank">Delete</a>';


                    return $btn;
                })
                ->editColumn('bank_id', function ($row) {

                    return $row->bank->name;
                })
                ->editColumn('hr_employee_id', function ($row) {

                    return $row->hrEmployee->employee_no . '- ' . $row->hrEmployee->first_name . ' ' . $row->hrEmployee->last_name . ', ' . $row->hrEmployee->designation;
                })
                ->rawColumns(['Edit', 'Delete'])
                ->make(true);
        }

        $employees = HrEmployee::with('employeeCurrentDesignation')->where('hr_status_id', 1)->get();
        $employeeBanks = EmployeeBank::with('hrEmployee', 'bank')->get();
        $banks = Bank::all();
        return view('common.bank.list', compact('employeeBanks', 'banks', 'employees'));
    }

    public function index()
    {

        $employeeBanks = EmployeeBank::where('hr_employee_id', session('hr_employee_id'))->get();
        $banks = Bank::all();

        $view =  view('hr.bank.create', compact('employeeBanks', 'banks'))->render();
        return response()->json($view);
    }

    public function create(Request $request)
    {

        if ($request->ajax()) {
            $data = EmployeeBank::where('hr_employee_id', session('hr_employee_id'))->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editEmployeeBank">Edit</a>';

                    return $btn;
                })
                ->addColumn('Delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteEmployeeBank">Delete</a>';


                    return $btn;
                })
                ->editColumn('bank_id', function ($row) {

                    return $row->bank->name;
                })
                ->rawColumns(['Edit', 'Delete'])
                ->make(true);
        }

        $employeeBanks = EmployeeBank::where('hr_employee_id', session('hr_employee_id'))->get();
        $banks = Bank::all();

        $view =  view('hr.bank.create', compact('employeeBanks', 'banks'))->render();
        return response()->json($view);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'bank_id' =>  ['required'],
            'hr_employee_id' =>  ['required'],
            'account_no' => ['required', Rule::unique('employee_banks')->where(fn ($query) => $query->where('bank_id', $request->bank_id)->where('id', '!=', $request->employee_bank_id))]
        ]);


        $input = $request->all();

        DB::transaction(function () use ($input) {

            EmployeeBank::updateOrCreate(
                ['id' => $input['employee_bank_id']],
                [
                    'bank_id' => $input['bank_id'],
                    'account_no' => $input['account_no'],
                    'hr_employee_id' => $input['hr_employee_id']
                ]
            );
        }); // end transcation      

        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $data = EmployeeBank::find($id);

        return response()->json($data);
    }

    public function destroy($id)
    {


        DB::transaction(function () use ($id) {
            EmployeeBank::find($id)->delete();
        }); // end transcation 

        return response()->json(['success' => 'data  delete successfully.']);
    }


    public function view()
    {
        $banks = Bank::all();
        return view('hr.bank.import', compact('banks'));
    }

    public function import(Request $request)
    {

        $this->validate($request, [
            'excel_file'  => 'required|mimes:xls,xlsx'
        ]);

        //$path = $request->file('excel_file')->getRealPath();
        $path1 = $request->file('excel_file')->store('temp');
        $path = storage_path('app') . '/' . $path1;

        \Excel::import(new BankDetailImport, $path);

        return back()->with('success', 'Excel Data Imported successfully.');
    }
}
