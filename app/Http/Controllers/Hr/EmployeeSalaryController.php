<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrSalary;
use App\Models\Hr\EmployeeSalary;
use DB;
use DataTables;
use App\Imports\EmployeeSalaryImport;

class EmployeeSalaryController extends Controller
{
    
    public function index() {
        
        $hrSalaries = HrSalary::all();
        
        $employeeSalaries = EmployeeSalary::where('hr_employee_id',session('hr_employee_id'))->get();

        $view =  view('hr.salary.create', compact('hrSalaries','employeeSalaries'))->render();
        return response()->json($view);

    }

    public function create(Request $request) {

        if ($request->ajax()) {
            $data = EmployeeSalary::where('hr_employee_id',session('hr_employee_id'))->latest()->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('Edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editSalary">Edit</a>';
                                                     
                            return $btn;
                    })
                    ->addColumn('Delete', function($row){                
                      
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteSalary">Delete</a>';
                            
                           
                            return $btn;
                    })
                    ->addColumn('totalSalary', function($row){                
                      
                           return addComma($row->hrSalary->total_salary);
                           
                    })
                    ->rawColumns(['Edit','Delete','totalSalary'])
                    ->make(true);
        }

        $hrSalaries = HrSalary::all();
        
        $employeeSalaries = EmployeeSalary::where('hr_employee_id',session('hr_employee_id'))->get();

        $view =  view('hr.salary.create', compact('hrSalaries','employeeSalaries'))->render();
        return response()->json($view);

    }

    public function store (Request $request){

    	$input = $request->all();
    	if($request->filled('effective_date')){
            $input ['effective_date'] = \Carbon\Carbon::parse($request->effective_date)->format('Y-m-d');
        }

         $input ['hr_salary']= intval(str_replace( ',', '', $request->hr_salary));

         DB::transaction(function () use ($input) {  
        	
           	 $hrSalary = HrSalary::where('total_salary',$input ['hr_salary'])->first();
           	 if(!$hrSalary){
           	 	$hrSalary = HrSalary::create(['total_salary'=>$input ['hr_salary']]);
           	 }

            EmployeeSalary::updateOrCreate(['id' => $input['employee_salary_id']],
                ['effective_date'=> $input['effective_date'],
                'hr_salary_id'=> $hrSalary->id,
                'hr_employee_id'=> $input['hr_employee_id']]); 
     
        }); // end transcation      
       
       return response()->json(['success'=>'Data saved successfully.']);

    }

    public function edit($id)
    {
        $employeeSalary = EmployeeSalary::with('hrSalary')->find($id);

        return response()->json($employeeSalary);
    }

    public function destroy ($id){

    	$employeeSalary = EmployeeSalary::where('hr_employee_id',session('hr_employee_id'))->first();

        if($employeeSalary->id==$id)
        {
            return response()->json(['error'=>'data  not deleted. data update from Appointment']);
        }

    	DB::transaction(function () use ($id) {  
    		EmployeeSalary::find($id)->delete();  		   	
    	}); // end transcation 
        return response()->json(['success'=>'data  delete successfully.']);

    }

    public function import(Request $request)
    {
     	
     $this->validate($request, [
      'select_file'  => 'required|mimes:xls,xlsx'
     ]);

     	$path = $request->file('select_file')->getRealPath();
        

     	\Excel::import(new EmployeeSalaryImport, $path);
         
    	return back()->with('success', 'Excel Data Imported successfully.');
    }


}
