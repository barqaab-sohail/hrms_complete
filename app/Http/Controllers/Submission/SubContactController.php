<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Submission\SubContact;
use App\Http\Requests\Submission\SubContactStore;
use DB;
use DataTables;


class SubContactController extends Controller
{
    public function create(Request $request){

		if($request->ajax()){
   			$data = SubContact::where('submission_id',session('submission_id'))->orderBy('id','desc')->get();
   			return DataTables::of($data)
   			
   			->addColumn('Edit', function($data){

                    if(Auth::user()->hasPermissionTo('sub edit record')){
                           
                         $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="btn btn-success btn-sm editSubmissionContact">Edit</a>';

                        return $button;
                    } 

            })
            ->addColumn('Delete', function($data){
                    if(Auth::user()->hasPermissionTo('sub edit record')){
                         $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteSubmissionContact">Delete</a>';
                         return $button;
                    	}
                    })

            ->rawColumns(['Edit','Delete'])
            ->make(true);

   		}

	}

	public function index(){
    	
	    $view =  view('submission.contact.create')->render();
	    return response()->json($view);
	}

	public function store(SubContactStore $request){
	    $input = $request->all();
	    $input['submission_id']=session('submission_id');

	        DB::transaction(function () use ($input) {  

	       		SubContact::updateOrCreate(['id' => $input['sub_contact_id']],$input); 

	      }); // end transcation

	    return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);
	}

	public function edit($id){
		$data = SubContact::find($id);
    	return response()->json($data);
	}


	public function destroy($id)
    {
        DB::transaction(function () use ($id) {  
            SubContact::findOrFail($id)->delete();   
        }); // end transcation

        return response()->json(['success'=>'data  delete successfully.']);
   
    }

}
