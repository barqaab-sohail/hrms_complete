<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Submission\SubParticipateRole;
use App\Http\Requests\Submission\PartnerStore;
use App\Models\Submission\SubCost;
use App\Models\Submission\Submission;
use App\Models\Common\Partner;
use App\Models\Project\PrRole;
use DB;
use DataTables;

class PartnerController extends Controller
{
    
	public function create(Request $request){

		if($request->ajax()){
   			$data = SubParticipateRole::where('submission_id',session('submission_id'))->orderBy('id','desc')->get();
   			return DataTables::of($data)
   			->addColumn('mm_cost', function($data){
                    
                         return addComma($data->subCost->mm_cost??'');
                    	
                    })
   			->addColumn('direct_cost', function($data){
                   
                        return addComma($data->subCost->direct_cost??'');
                    	
                    })
   			->addColumn('total_cost', function($data){
                   
                         return addComma($data->subCost->total_cost??'');
                    	
                    })
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

            ->rawColumns(['Edit','Delete','mm_cost','direct_cost','total_cost'])
            ->make(true);

   		}

   		//return view ('submission.partner.create');
	}

	public function index(){
    	
	    $partners = Partner::all();
	    $prRoles = PrRole::all();
	    $data = Submission::find(session('submission_id'));
	    $view =  view('submission.partner.create', compact('partners','prRoles','data'))->render();
	    return response()->json($view);
		// return response()->json(["partners"=>$partners, "prRoles"=>$prRoles]);
	}

	public function store(PartnerStore $request){
	    $input = $request->all();
	        $input['submission_id']=session('submission_id');
	        DB::transaction(function () use ($input, $request) {  

	       	$subParticipateRole = SubParticipateRole::updateOrCreate(['id' => $input['sub_participate_role_id']],$input); 

	       	$input['sub_participate_role_id']=$subParticipateRole->id;
	       	
	       	
	       	if($request->filled('total_cost')){
	       		$input ['mm_cost']= intval(str_replace( ',', '', $request->mm_cost));
	       		$input ['direct_cost']= intval(str_replace( ',', '', $request->direct_cost));
	       		$input ['total_cost']= intval(str_replace( ',', '', $request->total_cost));
	       		$subCost = SubCost::where('sub_participate_role_id',$input['sub_participate_role_id'])->first();
	       		SubCost::updateOrCreate(['id' => $subCost->id??''],$input); 
	       	}else{

	       		$subCost = SubCost::where('sub_participate_role_id',$input['sub_participate_role_id'])->first();
	       		if($subCost){
	       			 SubCost::findOrFail($subCost->id)->delete();  
	       		}
	       	}


	      }); // end transcation

	    return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);
	}

	public function edit($id){
		$data = SubParticipateRole::with('SubCost')->find($id);
    	return response()->json($data);
	}



	public function destroy($id)
    {
        DB::transaction(function () use ($id) {  
            SubParticipateRole::findOrFail($id)->delete();   
        }); // end transcation

        return response()->json(['success'=>'data  delete successfully.']);
   
    }


}
