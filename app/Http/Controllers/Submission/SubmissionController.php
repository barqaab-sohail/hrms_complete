<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
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

class SubmissionController extends Controller
{
    public function index(){
   
	$submissions =collect();
    $waterSubmissions = Submission:: where('sub_division_id',1)->get();
    $powerSubmissions = Submission::where('sub_division_id',2)->get();
	
	return view ('submission.submission.list', compact('submissions','waterSubmissions','powerSubmissions'));

	}

	public function create(){
    
	    $clients = Client::all();
	    $subTypes = SubType::all();
	    $eoiReferences = Submission::where('sub_type_id','!=',3)->get();
	    $divisions = PrDivision::all();
		
		return view ('submission.submission.create',compact('clients','subTypes','eoiReferences','divisions'));
	}

	public function store (SubmissionStore $request){
		
		$input = $request->all();

			if($request->filled('submission_date')){
	            $input ['submission_date']= \Carbon\Carbon::parse($request->submission_date)->format('Y-m-d');
	            }

	        //calculate Submission_no
	        $code = $request->sub_division_id . $request->sub_type_id . substr($request->submission_date,-2);
	        $count = 1;
			$submissionCode = $code.$count;

			while(Submission::where('submission_no',$code.$count)->count()>0){
				$count++;
				$submissionCode = $code.$count;
			}

			$input['submission_no']=$submissionCode;

        DB::transaction(function () use ($input, $request) {  

           $submission = Submission::create($input);
           $input['submission_id'] = $submission->id;
           if($request->filled('eoi_reference')){
           	SubRfpEoi::create($input);
           }
           SubDate::create($input);
           SubAddress::create($input);
           SubContact::create($input);

    	}); // end transcation

		return response()->json(['status'=> 'OK', 'message' => "Submission Successfully Saved and Sumission No. is $submissionCode"]);
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
		return back()->with('message', 'Data Successfully Updated');

	}




}
