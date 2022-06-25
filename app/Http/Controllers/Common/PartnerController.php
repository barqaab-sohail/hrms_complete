<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Common\PartnerStore;
use App\Models\Common\Partner;
use DB;
use DataTables;


class PartnerController extends Controller
{
   public function index(Request $request){
    	if($request->ajax()){
	    	$data = Partner::orderBy('id','desc')->get();
	    	return DataTables::of($data)	
	           
	            ->addColumn('edit', function($data){
	        
	                $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-success btn-sm editPartner">Edit</a>';

	                return $button;
	            })
	            ->addColumn('delete', function($data){
	                  
	                $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletePartner">Delete</a>';
	                return $button;
	            })
	            ->rawColumns(['edit','delete'])
	            ->make(true);
	    }
    	return view ('common.partner.list');
   	}

   	public function store (PartnerStore $request){
		
		$input = $request->all();

        DB::transaction(function () use ($input) {  

          Partner::updateOrCreate(['id' => $input['partner_id']],$input); 

    	}); // end transcation

		return response()->json(['status'=> 'OK', 'message' => "Submission Successfully Saved"]);
	}

   	public function edit($id){
		$data = Partner::find($id);
    	return response()->json($data);
	}

	public function destroy($id)
    {
        DB::transaction(function () use ($id) {  
            Partner::findOrFail($id)->delete();   
        }); // end transcation

        return response()->json(['success'=>'data  delete successfully.']);
   
    }
}
