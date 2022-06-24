<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Common\DegreeStore;
use App\Models\Common\Education;
use DB;
use DataTables;


class DegreeController extends Controller
{
    public function index(Request $request){
    	if($request->ajax()){
	    	$data = Education::orderBy('id','desc')->get();
	    	return DataTables::of($data)	
	           
	            ->addColumn('edit', function($data){
	        
	                $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-success btn-sm editDegree">Edit</a>';

	                return $button;
	            })
	            ->addColumn('delete', function($data){
	                  
	                $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteDegree">Delete</a>';
	                return $button;
	            })
	            ->rawColumns(['edit','delete'])
	            ->make(true);
	    }
    	return view ('common.degree.list');
   	}

   		public function store (DegreeStore $request){
		
		$input = $request->all();

        DB::transaction(function () use ($input) {  

          Education::updateOrCreate(['id' => $input['degree_id']],$input); 

          
    	}); // end transcation

		return response()->json(['status'=> 'OK', 'message' => "Submission Successfully Saved"]);
	}

   	public function edit($id){
		$data = Education::find($id);
    	return response()->json($data);
	}

	public function destroy($id)
    {
        DB::transaction(function () use ($id) {  
            Education::findOrFail($id)->delete();   
        }); // end transcation

        return response()->json(['success'=>'data  delete successfully.']);
   
    }
}
