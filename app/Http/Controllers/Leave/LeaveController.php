<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Leave\LeaveStore;
use App\Models\Leave\Leave;
use App\Models\Leave\LeHalfDay;
use App\Models\Leave\LeStatusType;
use App\Models\Leave\LeType;
use App\Models\Leave\LePerformDuty;
use App\Models\Hr\HrEmployee;
use DB;
use DataTables;


class LeaveController extends Controller
{
    
	public function create() {  
		session()->put('leave_id', '');

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

            	$status='';

	            if($data->leSanctioned){
	                $status = leaveStatusType($data->leSanctioned->le_status_type_id);
	            }else{
	            	$status = 'Pending';
	            }

            return '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-success btn-sm editStatus">'.$status.'</a>';
                
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
					'time'=>$input ['from'],
                     'description'=>'from'
					]);
			}

			if($request->filled('halfTo')){
				
				LeHalfDay::create([
					'leave_id'=>$leaveId->id,
					'time'=>$input ['to'],
                    'description'=>'to'
					]);
			}

    	}); // end transcation


		return response()->json(['status'=> 'OK', 'message' => "Data Successfully Update"]);
	}

	public function edit(Request $request, $id){
		session()->put('leave_id', $id);
		$employees = HrEmployee::whereIn('hr_status_id',array(1,5))->get();
		$data = Leave::find($id);
		$leaveTypes = LeType::all();
		$leHalfDayTo = LeHalfDay::where('leave_id',$id)->where('description','to')->count();
		$leHalfDayFrom = LeHalfDay::where('leave_id',$id)->where('description','from')->count();

		return view ('leave.edit', compact('employees','data','leaveTypes','leHalfDayTo','leHalfDayFrom'));

	}

	public function update (LeaveStore $request, $id){

		//ensure client end id is not changed
        if($id != session('leave_id')){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }

		$input = $request->all();

		DB::transaction(function () use ($input, $request, $id) {  
           
           $leaveId = Leave::findOrFail($id)->update($input);

           if($request->filled('perform_duty_id')){

				LePerformDuty::updateOrCreate(['leave_id' => $id],
                ['leave_id'=>$id,
				'hr_employee_id'=>$input ['perform_duty_id']
				]);
			}else{
				
				$lePerformDuty = LePerformDuty::where('leave_id',$id)->first();
				if($lePerformDuty){
					$lePerformDuty->delete();
				}

			}

           if($request->filled('halfFrom')){
				
				LeHalfDay::updateOrCreate(['leave_id' => $id, 'description'=>'from'],
                ['leave_id'=>$id,
					'time'=>$input ['from'],
                     'description'=>'from'
				]);
			}else{
				$halfLeaveFrom = LeHalfDay::where('leave_id',$id)->where('description','from')->first();
				if($halfLeaveFrom){
					$halfLeaveFrom->delete();
				}

			}

			if($request->filled('halfTo')){
				
				LeHalfDay::updateOrCreate(['leave_id' => $id, 'description'=>'to'],
                	['leave_id'=>$id,
					'time'=>$input ['to'],
                    'description'=>'to'
				]);
			}else{
				$halfLeaveTo = LeHalfDay::where('leave_id',$id)->where('description','to')->first();
				if($halfLeaveTo){
					$halfLeaveTo->delete();
				}

			}

    	}); // end transcation


		return response()->json(['status'=> 'OK', 'message' => "Data Successfully Update"]);
	}

	public function destroy($id){
    	
        DB::transaction(function () use ($id) {  

    		Leave::find($id)->delete();
    		   	
    	}); // end transcation
        
       return back()->with('message', 'Data Successfully Updated');
    }
}
