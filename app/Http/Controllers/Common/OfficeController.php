<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Office;
use DB;
use DataTables;

class OfficeController extends Controller
{
    
    public function index(Request $request){
    	if($request->ajax()){
	    	$data = Office::orderBy('id','desc')->get();
	    	return DataTables::of($data)	
	            ->addColumn('phone_no', function($data){
	        
	                return '';
	            })

	            ->addColumn('edit', function($data){
	        
	                $button = '<a class="btn btn-success btn-sm" href="'.route('office.edit',$data->id).'"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>';

	                return $button;
	            })
	            ->addColumn('delete', function($data){
	                  
	                $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteOffice">Delete</a>';
	                return $button;
	            })

	            ->rawColumns(['edit','delete','phone_no'])
	            ->make(true);
	    }
    	return view ('common.office.list');
   	}

   	public function edit(Request $request, $id){

		session()->put('office_id', $id);
		$data = Office::find($id);
        if($request->ajax()){      
            return view ('common.office.ajax', compact('data'));  
        }else{
            return view ('common.office.edit', compact('data'));      
        }    

	}


}
