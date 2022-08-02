<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Project\PrStaff;
use DB;
use DataTables;

class ProjectStaffController extends Controller
{
    public function index() {
        
        $hrEmployees = HrEmployee::all();
        
        $view =  view('project.staff.create', compact('hrEmployees'))->render();
        return response()->json($view);

    }

    public function create(Request $request){

        if ($request->ajax()) {
            $data = PrStaff::where('pr_detail_id',session('pr_detail_id'))->latest()->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('Edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editStaff">Edit</a>';
                                                     
                            return $btn;
                    })
                    ->addColumn('Delete', function($row){                
                      
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteStaff">Delete</a>';
                            
                           
                            return $btn;
                    })
                   
                    ->editColumn('hr_employee_id', function($row){                
                           return $row->hrEmployee->full_name??'';           
                    })

                    ->rawColumns(['Edit','Delete','hr_employee_id'])
                    ->make(true);
        }

	}

	public function store(Request $request){

		$input = $request->all();

     	DB::transaction(function () use ($input) {  
    		PrStaff::updateOrCreate(['id' => $input['staff_id']], $input); 
    	}); // end transcation

		return response()->json(['success'=>'Data saved successfully.']);
	}

	public function edit($id){
		$prStaff = PrStaff::find($id);
        return response()->json($prStaff);
	}

	public function destroy($id){
		PrStaff::findOrFail($id)->delete();
		return response()->json(['status'=> 'OK', 'message' => "Data Successfully Deleted"]);
	}

}
