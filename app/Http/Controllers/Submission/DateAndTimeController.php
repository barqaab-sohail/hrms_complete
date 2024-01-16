<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Submission\SubDate;
use DB;
use DataTables;

class DateAndTimeController extends Controller
{
    
	public function index(){
    	
	   
	    $view =  view('submission.date.create')->render();
	    return response()->json($view);
	}

	public function create(Request $request){

		if($request->ajax()){
   			$data = SubDate::where('submission_id',session('submission_id'))->orderBy('id','desc')->get();
   			return DataTables::of($data)
   			->addColumn('Edit', function($data){

                    if(Auth::user()->hasPermissionTo('sub edit record')){
                           
                         $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="btn btn-success btn-sm editSubmissionDate">Edit</a>';

                        return $button;
                    } 

            })
            ->addColumn('Delete', function($data){
                    if(Auth::user()->hasPermissionTo('sub edit record')){
                         $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteSubmissionDate">Delete</a>';
                         return $button;
                    	}
                    })
            ->editColumn('submission_date', function($data){  
                    return \Carbon\Carbon::parse($data->submission_date)->format('d-F-Y');
            })

            ->rawColumns(['Edit','Delete'])
            ->make(true);
   		}
	}

	public function store(Request $request){
	    $input = $request->all();
	    $input['submission_id']=session('submission_id');
	    $input ['submission_date']= \Carbon\Carbon::parse($request->submission_date)->format('Y-m-d');

        DB::transaction(function () use ($input) {  

       		SubDate::updateOrCreate(['id' => $input['sub_date_id']],$input); 

      }); // end transcation

	    return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);
	}

	public function edit($id){
		$data = SubDate::find($id);
    	return response()->json($data);
	}

	public function destroy($id)
    {
        DB::transaction(function () use ($id) {  
            SubDate::findOrFail($id)->delete();   
        }); // end transcation

        return response()->json(['success'=>'data  delete successfully.']);
   
    }

}
