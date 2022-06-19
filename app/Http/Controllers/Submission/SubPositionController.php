<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Submission\SubPosition;
use App\Models\Submission\SubNominatePerson;
use App\Models\Submission\SubManMonth;
use DB;
use DataTables;



class SubPositionController extends Controller
{
    public function index(){
    	$employees = HrEmployee::select(['id','first_name','last_name','employee_no'])->get();
	    $view =  view('submission.position.create',compact('employees'))->render();
	    return response()->json($view);
	}

	public function create(Request $request){

		if($request->ajax()){
   			$data = SubPosition::where('submission_id',session('submission_id'))->orderBy('id','desc')->get();
   			return DataTables::of($data)
   			->addColumn('Edit', function($data){
                $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="btn btn-success btn-sm editSubmissionPosition">Edit</a>';

                return $button;

            })
            ->addColumn('Delete', function($data){
                   
                 $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteSubmissionPosition">Delete</a>';
                 return $button;
                    	
            })
            ->addColumn('name', function($data){  
                    return $data->subNominatePerson->name??'';
            })
            ->addColumn('man_month', function($data){  
                    return $data->subManMonth->man_month??'';
            })
            ->addColumn('cv', function($data){  
                    return $data->subCv->path??'';
            })

            ->rawColumns(['Edit','Delete','name','man_month','cv'])
            ->make(true);
   		}
	}

	public function store(Request $request){
	    $input = $request->all();
	        $input['submission_id']=session('submission_id');
	        DB::transaction(function () use ($input, $request){  

	        	$subPosition = SubPosition::updateOrCreate(['id' => $input['sub_position_id']],$input); 
	        	$input['sub_position_id']=$subPosition->id;

	        	if($request->filled('name')){

	        		$subNominatePerson= SubNominatePerson::where('sub_position_id',$input['sub_position_id'])->first();
	        		SubNominatePerson::updateOrCreate(['id' => $subNominatePerson->id??''],$input);
	        	}else{
	        		$subNominatePerson= SubNominatePerson::where('sub_position_id',$input['sub_position_id'])->first();
		       		if($subNominatePerson){
		       			 SubNominatePerson::findOrFail($subNominatePerson->id)->delete();  
		       		}
	        	}

	        	if($request->filled('man_month')){
	        		$subManMonth= SubManMonth::where('sub_position_id',$input['sub_position_id'])->first();
	        		SubManMonth::updateOrCreate(['id' => $subManMonth->id??''],$input);
	        	}else{
	        		$subManMonth= SubManMonth::where('sub_position_id',$input['sub_position_id'])->first();
		       		if($subManMonth){
		       			 SubManMonth::findOrFail($subManMonth->id)->delete();  
		       		}
	        	}


	        }); // end transcation

	    return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);
	}

	public function edit($id){
		
		$data = SubPosition::with('subManMonth','subNominatePerson','subCv')->find($id);
		return response()->json($data);
	}

	public function destroy($id)
    {
        DB::transaction(function () use ($id) {  
            SubPosition::findOrFail($id)->delete();   
        }); // end transcation

        return response()->json(['success'=>'data  delete successfully.']);
   
    }

}
