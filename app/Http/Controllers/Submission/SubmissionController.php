<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Common\Client;
use App\Models\Common\Partner;
use App\Models\Submission\SubDate;
use App\Models\Submission\SubDescription;
use App\Models\Submission\SubStatus;
use App\Models\Submission\SubFinancialType;
use App\Models\Submission\SubCvFormat;
use App\Models\Submission\SubType;
use App\Models\Submission\SubEvaluationType;
use App\Models\Submission\Submission;
use App\Models\Submission\SubEoiReference;
use App\Models\Submission\SubRfpEoi;
use App\Models\Project\PrDivision;
use App\Http\Requests\Submission\SubmissionStore;
use DB;
use DataTables;

class SubmissionController extends Controller
{
    
    public function index(Request $request){
   		if($request->ajax()){
   			$data = Submission::orderBy('id','desc')->get();

   			return DataTables::of($data)
       			
            ->editColumn('sub_type_id', function($data){
              
              return $data->subStatusType->name??'';

            })
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

            ->rawColumns(['edit','delete','sub_type_id'])
            ->make(true);

   		}

	
	return view ('submission.list');

	}

	public function create(){
    	session()->put('submission_id', '');
	    $clients = Client::all();
	    $subTypes = SubType::all();
	    $divisions = PrDivision::all();
      $subStatuses = SubStatus::all();
		  $subFinancialTypes = SubFinancialType::all();
      $subCvFormats = SubCvFormat::all();
     
    
		return response()->json(["divisions"=>$divisions, "clients"=>$clients, "subTypes"=>$subTypes,"subStatuses"=>$subStatuses, "subFinancialTypes"=>$subFinancialTypes, "subCvFormats"=>$subCvFormats]);
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

        DB::transaction(function () use ($input, $request) {  

           $submission = Submission::create($input);
           $input['submission_id'] = $submission->id;

           if($request->filled('eoi_reference')){
           		SubEoiReference::create($input);
           }
           if($request->filled('submission_date') && $request->filled('submission_time') && $request->filled('address')){
              $input ['submission_date']= \Carbon\Carbon::parse($request->submission_date)->format('Y-m-d');
              SubDate::create($input);
           }
           if($request->filled('sub_status_id')){
              SubDescription::create($input);
           }

    	}); // end transcation

		return response()->json(['status'=> 'OK', 'message' => "Submission Successfully Saved"]);
	}


	public function edit(Request $request, $id){

		session()->put('submission_id', $id);
		$data = Submission::find($id);
		$clients = Client::all();
	  $subTypes = SubType::all();
	  $eoiReferences = Submission::where('sub_type_id','!=',3)->get();
	  $divisions = PrDivision::all();
    $subStatuses = SubStatus::all();
    $subFinancialTypes = SubFinancialType::all();
    $subCvFormats = SubCvFormat::all();
    $subEvaluationTypes = SubEvaluationType::all();
	  session()->put('submission_id', $data->id);

        if($request->ajax()){      
            return view ('submission.ajax', compact('clients','subTypes','eoiReferences','divisions','subFinancialTypes','subCvFormats','subStatuses','data','subEvaluationTypes'));  
        }else{
            return view ('submission.edit', compact('clients','subTypes','eoiReferences','divisions','subFinancialTypes','subCvFormats','subStatuses','data','subEvaluationTypes'));      
        }    

	}


	public function update(SubmissionStore $request, $id){

		$input = $request->all();
		//ensure client end is is not changed
        if($id != session('submission_id')){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }

        DB::transaction(function () use ($input, $request, $id) {  
           
        	Submission::findOrFail($id)->update($input);
        	$input['submission_id']=$id;
        	$subEoiReference = SubEoiReference::where('submission_id',$id)->first();
          $subDescription = SubDescription::where('submission_id',$id)->first();
          
          if($subDescription){
            SubDescription::findOrFail($subDescription->id)->update($input);
          }else{
            SubDescription::create($input);
          }

        	if(!$subEoiReference){
        		$subEoiReferenceId=null;
        	}

        	if($request->filled('eoi_reference_id')){
           		
           		SubEoiReference::updateOrCreate(['id' => $subEoiReferenceId],
                ['submission_id'=> $id,
                'eoi_reference_id'=> $input['eoi_reference_id'],
                ]); 

           	}else{
           		if($subEoiReference){
           			SubEoiReference::findOrFail($subEoiReference->id)->delete();
           		}
           	}

    	}); // end transcation

    	return response()->json(['status'=> 'OK', 'message' => 'Data Successfully Updated']);
        
	}

	public function destroy($id){

		Submission::findOrFail($id)->delete();
		return response()->json(['success'=>'data  delete successfully.']);
	}


	public function addClient (Request $request){
		
		$validated = $request->validate([
        'name' => 'required|unique:clients|max:190',
    	]);
    	
        DB::transaction(function () use ($request) {  

            Client::create(["name"=>$request->name]);
           
    	}); // end transcation

    	$clients = Client::all();

		return response()->json(['status'=> 'OK', 'message' => "Submission Successfully Saved", 'clients'=>$clients]);
	}

    public function addPartner(Request $request){
    
    $validated = $request->validate([
        'name' => 'required|unique:clients|max:190',
      ]);
      
        
        DB::transaction(function () use ($request) {  

            Partner::create(["name"=>$request->name]);
           
      }); // end transcation

      $partners = Partner::all();

    return response()->json(['status'=> 'OK', 'message' => "Submission Successfully Saved", 'partners'=>$partners]);
  }





}
