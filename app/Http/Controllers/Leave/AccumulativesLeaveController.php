<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave\LeAccumulative;
use App\Models\Hr\HrEmployee;
use App\Models\Leave\LeType;
use DB;
use DataTables;

class AccumulativesLeaveController extends Controller
{
    public function index() {  
        $leAccumulatives = LeAccumulative::all();
        	$result = collect(HrEmployee::join('employee_categories','employee_categories.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_categories.hr_category_id','employee_categories.effective_date as cat')->whereIn('hr_status_id',array(1,5))->orderBy('cat','desc')->get());
            	$resultUnique = ($result->unique('id'));
            	$resultUnique->values()->all();
        $hrEmployees = $resultUnique->where('hr_category_id',1);
        $leTypes = LeType::where('name','Annual Leave')->get();
        $view =  view('leave.accumulative_leave.create', compact('leAccumulatives','hrEmployees','leTypes'))->render();
        return $view;
    }

     public function create(Request $request) {

        if ($request->ajax()) {
            $data = LeAccumulative::latest()->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('Edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editAccumulativeLeave">Edit</a>';
                                                     
                            return $btn;
                    })
                    ->addColumn('Delete', function($row){                
                      
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteAccumulativeLeave">Delete</a>';
                            
                           
                            return $btn;
                    })
                    ->addColumn('fullName', function($row){                
                      
                           return employeeFullName($row->hr_employee_id);
                           
                    })

                    ->rawColumns(['Edit','Delete','fullName'])
                    ->make(true);
        }

       $leAccumulatives = LeAccumulative::all();
        	$result = collect(HrEmployee::join('employee_categories','employee_categories.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_categories.hr_category_id','employee_categories.effective_date as cat')->whereIn('hr_status_id',array(1,5))->orderBy('cat','desc')->get());
            	$resultUnique = ($result->unique('id'));
            	$resultUnique->values()->all();
        $hrEmployees = $resultUnique->where('hr_category_id',1);
        $leTypes = LeType::where('name','Annual Leave')->get();
        $view =  view('leave.accumulative_leave.create', compact('leAccumulatives','hrEmployees','leTypes'))->render();
        return response()->json($view);

    }

    public function store (Request $request){

    	$input = $request->all();
    	if($request->filled('date')){
            $input ['date'] = \Carbon\Carbon::parse($request->date)->format('Y-m-d');
        }

         DB::transaction(function () use ($input) {  

            LeAccumulative::updateOrCreate(['id' => $input['le_accumulative_id']],
                ['date'=> $input['date'],
                'le_type_id'=> $input['le_type_id'],
                'hr_employee_id'=> $input['hr_employee_id'],
                'accumulative_total'=> $input['accumulative_total']]); 
            //$data = HrManager::create($input);
        }); // end transcation      
       return response()->json(['success'=>'Data saved successfully.']);

    }




}
