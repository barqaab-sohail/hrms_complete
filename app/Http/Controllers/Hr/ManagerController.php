<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\EmployeeManager;
use App\Models\Hr\HrEmployee;
use DB;
use DataTables;

class ManagerController extends Controller
{

    public function index() {
    	
       	$employees = HrEmployee::all();
        
       	$managers = EmployeeManager::where('hr_employee_id',session('hr_employee_id'))->get();
        //dd($managers->hodDesignation->name);
        $view =  view('hr.manager.create', compact('employees','managers'))->with('hodDesignation','hrDesignation')->render();
        return response()->json($view);

    }

    public function create(Request $request) {

        if ($request->ajax()) {
            $data = EmployeeManager::where('hr_employee_id',session('hr_employee_id'))->latest()->with('hrEmployee','hodDesignation')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('Edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editManager">Edit</a>';
                                                     
                            return $btn;
                    })
                    ->addColumn('Delete', function($row){                
                      
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteManager">Delete</a>';
                            
                           
                            return $btn;
                    })
                    ->addColumn('fullName', function($row){                
                      
                           return employeeFullName($row->hr_manager_id);
                           
                    })
                    ->rawColumns(['Edit','Delete','fullName'])
                    ->make(true);
        }

        $employees = HrEmployee::where('hr_status_id',1)->get();
        $managers = EmployeeManager::where('hr_employee_id',session('hr_employee_id'))->get();
        
        $view =  view('hr.manager.create', compact('employees','managers'))->render();
        return response()->json($view);

    }

    public function store (Request $request){

    	$input = $request->all();
    	if($request->filled('effective_date')){
            $input ['effective_date'] = \Carbon\Carbon::parse($request->effective_date)->format('Y-m-d');
        }

        

         DB::transaction(function () use ($input) {  
        	
            //EmployeeManager::updateOrCreate(['id' => $input['hr_manager_id']],
            //    $input); 

            EmployeeManager::updateOrCreate(['id' => $input['employee_manager_id']],
                ['effective_date'=> $input['effective_date'],
                'hr_manager_id'=> $input['hr_manager_id'],
                'hr_employee_id'=> $input['hr_employee_id']]); 
            //$data = HrManager::create($input);
        }); // end transcation      
       return response()->json(['success'=>'Data saved successfully.']);

    }



    public function edit($id)
    {
        $manager = EmployeeManager::find($id);
        return response()->json($manager);
    }

    
    public function destroy ($id){

        $employeeManager = EmployeeManager::where('hr_employee_id',session('hr_employee_id'))->first();

        if($employeeManager->id==$id)
        {
            return response()->json(['error'=>'data  not deleted. data update from Appointment']);
        }

    	DB::transaction(function () use ($id) {  
    		EmployeeManager::find($id)->delete();  		   	
    	}); // end transcation 
        return response()->json(['success'=>'data  delete successfully.']);

    }


}
