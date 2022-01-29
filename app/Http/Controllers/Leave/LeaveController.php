<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Leave\LeaveStore;
use App\Models\Leave\Leave;
use App\Models\Leave\LeHalfDay;
use App\Models\Leave\LePerformDuty;
use App\Models\Hr\HrEmployee;
use DB;
use DataTables;


class LeaveController extends Controller
{
    
	public function create() {  
		$employees = HrEmployee::whereIn('hr_status_id',array(1,5))->get();

		 return view ('leave.create', compact('employees'));

	}

	public function index(Request $request){
   
        if($request->ajax()){

            $data = Leave::with('hrEmployee','employeeDesignation')->get();   
           
            return DataTables::of($data)
            ->addColumn('employee_no', function($data){
                $employee_no = $data->hrEmployee->employee_no;

                return $employee_no;
            })
            ->addColumn('full_name', function($data){
                $full_name = $data->hrEmployee->first_name . ' '. $data->hrEmployee->last_name;

                return $full_name;
            })
            ->addColumn('designation',function($data){
                return $data->employeeDesignation->last()->name??'';
            })
            ->addColumn('status',function($data){

                
            if($data->leSanctioned){
                return leaveStatusType($data->leSanctioned->le_status_type_id);
            }

            return 'Pending'; 
                
            })
           
            ->addColumn('edit', function($data){
       
            $button = '<a class="btn btn-success btn-sm" href="'.route('leave.edit',$data->id).'"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>';

            return $button;  

            })
            ->addColumn('delete', function($data){
                        $button = '<form  id="formDeleteLeave'.$data->id.'"  action="'.route('leave.destroy',$data->id).'" method="POST">'.method_field('DELETE').csrf_field().'
                                 <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you Sure to Delete\')" href= data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-trash-alt"></i></button>
                                 </form>';
                        return $button;
                    })
            ->rawColumns(['employee_no','full_name','designation','status','edit','delete'])
            ->make(true);
        }

       return view ('leave.list');
       
    }

	public function leaveType($employeeId){

			$employee = HrEmployee::find($employeeId);

			if ($employee->employeeCategory->last()->name =='A'){
				$states = DB::table("le_types")
                ->pluck("name","id");
            }else {
            	$states = DB::table("le_types")
            	->whereIn('id',array(1,3,4))
                ->pluck("name","id");
            }
	    return response()->json($states);
	}

	public function store (LeaveStore $request){


		$input = $request->all();

		DB::transaction(function () use ($input, $request) {  
           
           $leaveId = Leave::create($input);

           if($request->filled('perform_duty_id')){
				
				LePerformDuty::create([
					'leave_id'=>$leaveId->id,
					'hr_employee_id'=>$input ['perform_duty_id']
					]);
			}

           if($request->filled('halfFrom')){
				
				LeHalfDay::create([
					'leave_id'=>$leaveId->id,
					'date'=>$input ['from'],
                     'description'=>'Half Day Leave on '. $input ['from']
					]);
			}

			if($request->filled('halfTo')){
				
				LeHalfDay::create([
					'leave_id'=>$leaveId->id,
					'date'=>$input ['to'],
                    'description'=>'Half Day Leave on '. $input ['to']
					]);
			}

    	}); // end transcation


		

		return response()->json(['status'=> 'OK', 'message' => "Data Successfully Update"]);
	}
}
