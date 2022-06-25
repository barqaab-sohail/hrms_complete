<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Common\ClientStore;
use App\Models\Common\Client;
use DB;
use DataTables;

class ClientController extends Controller
{
    public function index(Request $request){
    	if($request->ajax()){
	    	$data = Client::orderBy('id','desc')->get();
	    	return DataTables::of($data)	
	           
	            ->addColumn('edit', function($data){
	        
	                $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-success btn-sm editClient">Edit</a>';

	                return $button;
	            })
	            ->addColumn('delete', function($data){
	                  
	                $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteClient">Delete</a>';
	                return $button;
	            })
	            ->rawColumns(['edit','delete'])
	            ->make(true);
	    }
    	return view ('common.client.list');
   	}

   	public function store (ClientStore $request){
		
		$input = $request->all();

        DB::transaction(function () use ($input) {  

          Client::updateOrCreate(['id' => $input['client_id']],$input); 

    	}); // end transcation

		return response()->json(['status'=> 'OK', 'message' => "Submission Successfully Saved"]);
	}

   	public function edit($id){
		$data = Client::find($id);
    	return response()->json($data);
	}

	public function destroy($id)
    {
        DB::transaction(function () use ($id) {  
            Client::findOrFail($id)->delete();   
        }); // end transcation

        return response()->json(['success'=>'data  delete successfully.']);
   
    }
}
