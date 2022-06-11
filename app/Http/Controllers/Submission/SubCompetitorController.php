<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Submission\SubCompetitor;
use App\Models\Submission\SubTechnicalNumber;
use App\Models\Submission\SubFinancialCost;
use App\Models\Submission\SubResult;
use App\Models\Submission\Currency;
use DB;
use DataTables;

class SubCompetitorController extends Controller
{
    
	public function index(){
    	
	    $currencies = Currency::all();
	    $view =  view('submission.competitor.create', compact('currencies'))->render();
	    return response()->json($view);
	}

	public function create(Request $request){

		if($request->ajax()){
   			$data = SubCompetitor::where('submission_id',session('submission_id'))->orderBy('id','desc')->get();
   			return DataTables::of($data)
   			->addColumn('technical_number', function($data){
                    
                         return $data->subTechnicalNumber->technical_number;
                    	
                })
   			->addColumn('technical_score', function($data){
                    
                         return $data->getTechnicalScore();
                    	
                })
   			->addColumn('financial_cost', function($data){
                    
                        return  $data->subFinancialCost->financial_cost??'';
                    	
                })
   			->addColumn('financial_score', function($data){
                    
                         return $data->getFinancialMark();
                    	
                })
   			->addColumn('technical_financial_score', function($data){
                    
                        return  $data->getTechnicalAndFinancialMark();
                    	
                    })
   			->addColumn('rank', function($data){
                    	                        
                        return  $data->getRanking();
                    	
                    })
   			
   			->addColumn('Edit', function($data){

                    if(Auth::user()->hasPermissionTo('sub edit record')){
                           
                         $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="btn btn-success btn-sm editSubmissionCompetitor">Edit</a>';

                        return $button;
                    } 

            })
            ->addColumn('Delete', function($data){
                    if(Auth::user()->hasPermissionTo('sub edit record')){
                         $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteSubmissionCompetitor">Delete</a>';
                         return $button;
                    	}
                    })

            ->rawColumns(['Edit','Delete','financial_cost','technical_number','technical_score','financial_score','technical_financial_score','rank'])
            ->make(true);

   		}

	}

	public function store(Request $request){
	    $input = $request->all();
	        $input['submission_id']=session('submission_id');
	        DB::transaction(function () use ($input, $request) {  

		       	$subCompetitor = SubCompetitor::updateOrCreate(['id' => $input['sub_competitor_id']],$input); 

		       	$input['sub_competitor_id']=$subCompetitor->id;
		       	
		       	
		       	if($request->filled('technical_number')){
		       		$subTechnicalNumber = SubTechnicalNumber::where('sub_competitor_id',$input['sub_competitor_id'])->first();
		       		SubTechnicalNumber::updateOrCreate(['id' => $subTechnicalNumber->id??''],$input); 
		       	}else{
		       		$subTechnicalNumber = SubTechnicalNumber::where('sub_competitor_id',$input['sub_competitor_id'])->first();
		       		if($subTechnicalNumber){
		       			 SubTechnicalNumber::findOrFail($subTechnicalNumber->id)->delete();  
		       		}
		       	}
		       	if($request->filled('financial_cost')){
		       		$subFinancialCost = SubFinancialCost::where('sub_competitor_id',$input['sub_competitor_id'])->first();
		       		SubFinancialCost::updateOrCreate(['id' => $subFinancialCost->id??''],$input); 
		       	}else{
		       		$subFinancialCost = SubFinancialCost::where('sub_competitor_id',$input['sub_competitor_id'])->first();
		       		if($subFinancialCost){
		       			 SubFinancialCost::findOrFail($subFinancialCost->id)->delete();  
		       		}
		       	}

	       		// if($request->filled('currency_id')){
		       	// 	$subFinancialCost = SubFinancialCost::where('sub_competitor_id',$input['sub_competitor_id'])->first();
		       	// 	SubFinancialCost::updateOrCreate(['id' => $subFinancialCost->id??''],$input); 
		       	// }else{
		       	// 	$subFinancialCost = SubFinancialCost::where('sub_competitor_id',$input['sub_competitor_id'])->first();
		       	// 	if($subFinancialCost){
		       	// 		 SubFinancialCost::findOrFail($subFinancialCost->id)->delete();  
		       	// 	}
		       	// }

	       		// if($request->filled('technical_financial_score')){
		       	// 	$subResult = subResult::where('sub_competitor_id',$input['sub_competitor_id'])->first();
		       	// 	subResult::updateOrCreate(['id' => $subResult->id??''],$input); 
		       	// }else{
		       	// 	$subResult = subResult::where('sub_competitor_id',$input['sub_competitor_id'])->first();
		       	// 	if($subResult){
		       	// 		 subResult::findOrFail($subResult->id)->delete();  
		       	// 	}
		       	// }


	      }); // end transcation

	    return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);
	}

	public function edit($id){
		$data = SubCompetitor::with('subTechnicalNumber','subFinancialCost')->find($id);
    	return response()->json($data);
	}

	public function destroy($id)
    {
        DB::transaction(function () use ($id) {  
            SubCompetitor::findOrFail($id)->delete();   
        }); // end transcation

        return response()->json(['success'=>'data  delete successfully.']);
   
    }




}
