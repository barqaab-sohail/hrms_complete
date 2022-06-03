<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Submission\SubParticipateRole;
use App\Models\Common\Partner;
use App\Models\Project\PrRole;
use DB;
use DataTables;

class PartnerController extends Controller
{
    
	public function index(Request $request){

		if($request->ajax()){
   			$data = SubParticipateRole::where('submission_id',session('submission_id'))->orderBy('id','desc')->get();
   			return DataTables::of($data)
   			->addColumn('Edit', function($data){

                    if(Auth::user()->hasPermissionTo('sub edit record')){
                           
                         $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="btn btn-success btn-sm editSubmissionPartner">Edit</a>';

                        return $button;
                    } 

            })
            ->addColumn('Delete', function($data){
                    if(Auth::user()->hasPermissionTo('sub edit record')){
                         $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteSubmissionPartner">Delete</a>';
                         return $button;
                    	}
                    })

            ->rawColumns(['Edit','Delete'])
            ->make(true);

   		}

   		//return view ('submission.partner.create');
	}

	public function list(){
		return view ('submission.partner.create');
	}

	public function create(){
    	
	    $partners = Partner::all();
	    $prRoles = PrRole::all();
		return response()->json(["partners"=>$partners, "prRoles"=>$prRoles]);
	}

	public function store(Request $request){
	    $input = $request->all();
	        $input['submission_id']=session('submission_id');
	        DB::transaction(function () use ($input) {  

	       		SubParticipateRole::updateOrCreate(['id' => $input['sub_participate_role_id']],$input); 

	      }); // end transcation

	    return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);
	}

	public function edit($id){

		$data = SubParticipateRole::find($id);
		$partners = Partner::all();
	    $prRoles = PrRole::all();
	   
       return response()->json(["partners"=>$partners, "prRoles"=>$prRoles, "data"=>$data]);
	}



	public function destroy($id)
    {
        DB::transaction(function () use ($id) {  
            subParticipateRole::findOrFail($id)->delete();   
        }); // end transcation

        return response()->json(['success'=>'data  delete successfully.']);
   
    }


}
