<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Common\DesignationStore;
use App\Models\Hr\HrDesignation;
use DB;
use DataTables;


class DesignationController extends Controller
{
    public function index(Request $request){
    	if($request->ajax()){
	    	$data = HrDesignation::orderBy('id','desc')->get();
	    	return DataTables::of($data)	
	           
	            ->addColumn('edit', function($data){
	        
	                $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-success btn-sm editDesignation">Edit</a>';

	                return $button;
	            })
	            ->addColumn('delete', function($data){
	                  
	                $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteDesignation">Delete</a>';
	                return $button;
	            })
	            ->rawColumns(['edit','delete'])
	            ->make(true);
	    }
    	return view ('common.designation.list');
   	}

   	public function store (DesignationStore $request){
		
		$input = $request->all();

        DB::transaction(function () use ($input) {  

          HrDesignation::updateOrCreate(['id' => $input['designation_id']],$input); 

          
    	}); // end transcation

		return response()->json(['status'=> 'OK', 'message' => "Submission Successfully Saved"]);
	}

   	public function edit($id){
		$data = HrDesignation::find($id);
    	return response()->json($data);
	}

	public function destroy($id)
    {
        DB::transaction(function () use ($id) {  
            HrDesignation::findOrFail($id)->delete();   
        }); // end transcation

        return response()->json(['success'=>'data  delete successfully.']);
   
    }
}
