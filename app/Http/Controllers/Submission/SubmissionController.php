<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Common\Client;
use App\Models\Submission\SubType;
use App\Models\Submission\Submission;
use App\Models\Submission\SubDate;
use App\Models\Submission\SubAddress;
use App\Models\Submission\SubContact;
use App\Models\Submission\SubRfpEoi;
use App\Models\Project\PrDivision;
use App\Http\Requests\Submission\SubmissionStore;
use DB;
use DataTables;

class SubmissionController extends Controller
{
    

    public function index(Request $request){
   		if($request->ajax()){
   			$data = Submission::all();

   			return DataTables::of($data)
   			->addColumn('edit', function($data){

                    if(Auth::user()->hasPermissionTo('sub edit record')){
                        
                        $button = '<a class="btn btn-success btn-sm" href="'.route('submission.edit',$data->id).'"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>';

                        return $button;
                    } 

            })
            ->addColumn('delete', function($data){
                    if(Auth::user()->hasPermissionTo('sub edit record')){
                         $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteSubmission">Delete</a>';
                         return $button;
                    	}
                    })

            ->rawColumns(['edit','delete'])
            ->make(true);

   		}

	$submissions =collect();
    $waterSubmissions = Submission:: where('sub_division_id',1)->get();
    $powerSubmissions = Submission::where('sub_division_id',2)->get();
	
	return view ('submission.list', compact('submissions','waterSubmissions','powerSubmissions'));

	}

	public function create(){
    
	    $clients = Client::all();
	    $subTypes = SubType::all();
	    $divisions = PrDivision::all();
		
		return response()->json(["divisions"=>$divisions, "clients"=>$clients, "subTypes"=>$subTypes]);
	}

	public function eoiReference(){
		$eoiReferences = Submission::where('sub_type_id','!=',3)->get();
		return response()->json($eoiReferences);
	}
	

	public function submissionNo($subTypeId){
		//asSubClass
        $lastSubmission = Submission::where('sub_type_id',$subTypeId)->orderBy('id','desc')->first();
        $submissionNo=null;
        if($lastSubmission){
        	$lastSubTypeId = $lastSubmission->submission_no;
        	$numbers = explode('-', $lastSubTypeId);
			$lastPart = end($numbers)+1;
			 $submissionNo = $subTypeId.'-'.$lastPart;
        }else{
        	$submissionNo = $subTypeId.'-'.'1001';
        }

        return response()->json($submissionNo);
    }

	public function store (SubmissionStore $request){
		
		$input = $request->all();

		
        DB::transaction(function () use ($input) {  

           $submission = Submission::create($input);
           $input['submission_id'] = $submission->id;

           // if($request->filled('eoi_reference')){
           // 	SubRfpEoi::create($input);
           // }
           // SubDate::create($input);
           // SubAddress::create($input);
           // SubContact::create($input);

    	}); // end transcation

		return response()->json(['status'=> 'OK', 'message' => "Submission Successfully Saved"]);
	}


	public function edit(Request $request, $id){

		session()->put('submission_id', $id);
		$data = Submission::find($id);
		$clients = Client::all();
	    $subTypes = SubType::all();
	    $eoiReferences = Submission::all();
	    $divisions = PrDivision::all();
  
	    if($request->ajax()){      
	            return view ('submission.submission.ajax', compact('clients','subTypes','eoiReferences','divisions','data'));    
	    }else{
	           return view ('submission.submission.edit', compact('clients','subTypes','eoiReferences','divisions','data'));     
	    }

	}


	public function update(Request $request, $id){

		$input = $request->all();

		if($request->filled('submission_date')){
	            $input ['submission_date']= \Carbon\Carbon::parse($request->submission_date)->format('Y-m-d');
	    }

        DB::transaction(function () use ($input, $request, $id) {  
           
        	Submission::findOrFail($id)->update($input);

        	$date = $request->only('submission_date','submittion_time');
        	$date ['submission_date']= \Carbon\Carbon::parse($request->submission_date)->format('Y-m-d');
        	$subDate = SubDate::where('submission_id',$id)->first();	
			SubDate::findOrFail($subDate->id)->update($date);

			$address = $request->only('address','designation');
			$subAddress = SubAddress::where('submission_id',$id)->first();	
			SubAddress::findOrFail($subAddress->id)->update($address);

			$contact = $request->only('landline','fax','mobile', 'email');
			$subContact = SubContact::where('submission_id',$id)->first();	
			SubContact::findOrFail($subContact->id)->update($contact);

    	}); // end transcation

    	return response()->json(['status'=> 'OK', 'message' => 'Data Successfully Updated']);
        
	}

	public function destroy($id){

		Submission::findOrFail($id)->delete();
		return response()->json(['success'=>'data  delete successfully.']);
	}




}
