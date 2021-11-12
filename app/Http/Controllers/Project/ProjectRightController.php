<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Common\Right;
use App\Http\Requests\Project\Rights\ProjectRightsStore;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Project\PrDetail;
use App\Models\Project\PrRight;
use App\Models\Admin\Permission;
use App\User;
use DB;
use DataTables;

class ProjectRightController extends Controller
{
    
   	
	public function index() {
       
       	$employees = HrEmployee::where('hr_status_id',1)->with('employeeDesignation')->get();
       	$projects = PrDetail::whereNotIn('name', array('overhead'))->get();
       	$rights = Right::all();

       	// return view('project.rights.create',compact('employees','projects','progressRights','invoiceRights'));
        $view =  view('project.rights.create',compact('employees','projects','rights'))->render();
        return $view;
    }

    public function create(Request $request){

        if ($request->ajax()) {

            $data = PrRight::all();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('Edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProjectRight">Edit</a>';
                                                     
                            return $btn;
                    })
                    ->addColumn('Delete', function($row){                
                      
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProjectRight">Delete</a>';
                            
                           
                            return $btn;
                    })
                    ->addColumn('hr_employee_id', function($row){                
                      
                           return employeeFullName($row->hr_employee_id);
                           
                    })
                    ->addColumn('pr_detail_id', function($row){                
                      	
                      	$project = PrDetail::find($row->pr_detail_id);
                        return $project->name;
                        //employeeFullName($row->pr_detail_id);
                           
                    })
                    ->addColumn('invoice', function($row){                
                      return rightsName($row->invoice);
                    })
                    ->addColumn('progress', function($row){                
                      return rightsName($row->progress);
                    })
                    ->addColumn('payment', function($row){                
                      return rightsName($row->payment);
                    })
                 
                    ->rawColumns(['Edit','Delete','hr_employee_id','pr_detail_id','invoice','progress'])
                    ->make(true);
        }

        $employees = HrEmployee::where('hr_status_id',1)->with('employeeDesignation')->get();
       	$projects = PrDetail::whereNotIn('name', array('overhead'))->get();

       	$progressRights = rights();
       	$invoiceRights = rights();

        $view =  view('project.rights.create',compact('employees','projects','progressRights','invoiceRights'))->render();

        return response()->json($view);

	}

	public function store(ProjectRightsStore $request){

        $input = $request->all();

        DB::transaction(function () use ($input, $request) {  

            PrRight::updateOrCreate(['id' => $input['right_id']],
                ['pr_detail_id'=> $input['pr_detail_id'],
                'hr_employee_id'=> $input['hr_employee_id'],
                'progress'=> $input['progress'],
                'invoice'=> $input['invoice'],
                'payment'=> $input['payment']  
            ]);
            
            $prLimitedAccess = Permission::where('name','pr limited access')->first();

            if($prLimitedAccess){
            	$employee = HrEmployee::where('id',$input['hr_employee_id'])->first();
            	$user = User::where('id',$employee->user_id)->first();
            	$user->givePermissionTo($prLimitedAccess->id);
            }


        }); // end transcation

        return response()->json(['success'=>'Data saved successfully.']);
    }

    public function edit($id){

		$prRight= PrRight::find($id);
 
    return response()->json($prRight);

	}

	public function destroy($id){
		$prRight = PrRight::find ($id);
		$employee = HrEmployee::find($prRight->hr_employee_id);
		$user = User::find($employee->user_id);
		$prLimitedAccess = Permission::where('name','pr limited access')->first();

		$totalProjectRights = PrRight::where('hr_employee_id',$employee->id)->count();
		
		if ($totalProjectRights == 1){
			$user->revokePermissionTo($prLimitedAccess->id);
		}
		
		PrRight::findOrFail($id)->delete();

        return response()->json(['success' => "Data Successfully Deleted"]);
        
    }





   // 	public function show($id){

			
			// $data = PrRight::where('hr_employee_id',3)->get();

	  // 		return view('project.rights.rightsTable',compact('data'));

   //  }



 //    public function create(){
    	
 //    	$rights = rights();
 //    	$employees = HrEmployee::all();
 //    	$projects = PrDetail::all();
	// 	return view ('project.rights.create', compact('employees','projects','rights'));
	// }


	// public function store(Request $request){



	// }

	



}
