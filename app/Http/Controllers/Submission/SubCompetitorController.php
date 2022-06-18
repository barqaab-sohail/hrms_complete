<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Submission\SubCompetitorStore;
use App\Models\Submission\Submission;
use App\Models\Submission\SubMultiCurrency;
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
	    $data = Submission::find(session('submission_id'));
	    $view =  view('submission.competitor.create', compact('currencies','data'))->render();
	    return response()->json($view);
	}

	public function create(Request $request){

		if($request->ajax()){
   			$data = SubCompetitor::where('submission_id',session('submission_id'))->orderBy('id','desc')->get();
   			return DataTables::of($data)
   			->addColumn('technical_number', function($data){
                    
                         return $data->subTechnicalNumber->technical_number??'';
                    	
                })
   			->addColumn('technical_score', function($data){
                if($data->submission->subDescription->sub_evaluation_type_id==1){
                         return $data->getTechnicalScore();
                }else{
                	return '';
                }
                    	
            })
   			->addColumn('total_price', function($data){
                    
                    return  addComma($data->subFinancialCost->total_price??'');
                    	
            })
   			->addColumn('financial_score', function($data){
                if($data->submission->subDescription->sub_evaluation_type_id==1){    
                         return $data->getFinancialMark();
                }else{
                	return '';
                }    	
            })
   			->addColumn('technical_financial_score', function($data){
                if($data->submission->subDescription->sub_evaluation_type_id==1){ 
                        return  $data->getTechnicalAndFinancialMark();
                }else{
                	return '';
                }   
                    	
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

            ->rawColumns(['Edit','Delete','total_price','technical_number','technical_score','financial_score','technical_financial_score','rank'])
            ->make(true);

   		}

	}

	public function store(SubCompetitorStore $request){
	    $input = $request->all();
	        $input['submission_id']=session('submission_id');
	        $input ['financial_cost']= intval(str_replace( ',', '', $request->financial_cost));
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
		       	if($request->filled('total_price')){
		       		$input['total_price']= intval(str_replace( ',', '', $request->input("total_price")));
		       		$subFinancialCost = SubFinancialCost::where('sub_competitor_id',$input['sub_competitor_id'])->first();
		       		SubFinancialCost::updateOrCreate(['id' => $subFinancialCost->id??''],$input); 
		       	}else{
		       		$subFinancialCost = SubFinancialCost::where('sub_competitor_id',$input['sub_competitor_id'])->first();
		       		if($subFinancialCost){
		       			 SubFinancialCost::findOrFail($subFinancialCost->id)->delete();  
		       		}

		       	}

		       	if ($request->input("currency_id.0")){
		       		
		       		$subMultiCurrencyIds=SubMultiCurrency::where('sub_competitor_id',$subCompetitor->id)->get()->pluck('id')->toArray();
		       		$requestMultiCurrencyIds = $request->input("sub_multi_currency_id");
		       		$subMultiCurrencies = SubMultiCurrency::where('sub_competitor_id',$subCompetitor->id)->get();

		       		for ($i=0;$i<count($request->input('currency_id'));$i++){
		       			$multiCurrency['sub_competitor_id'] = $subCompetitor->id;
		       			$multiCurrency['conversion_date'] = \Carbon\Carbon::parse($request->input("conversion_date.0"))->format('Y-m-d');
						$multiCurrency['currency_id'] = $request->input("currency_id.$i");
						$multiCurrency['conversion_rate'] = $request->input("conversion_rate.$i");
						$multiCurrency ['currency_price']= intval(str_replace( ',', '', $request->input("currency_price.$i")));

						SubMultiCurrency::updateOrCreate(['id' =>$request->input("sub_multi_currency_id.$i")],$multiCurrency);
					}
					
					foreach($subMultiCurrencies as $subMultiCurrency){
		       			if(!in_array($subMultiCurrency->id, $requestMultiCurrencyIds)){
            				SubMultiCurrency::findOrFail($subMultiCurrency->id)->delete();
        				}	
		       		}

		       	}else{
		       		$subMultiCurrencies = SubMultiCurrency::where('sub_competitor_id',$input['sub_competitor_id'])->get();
		       		if($subMultiCurrencies){
		       			foreach($subMultiCurrencies as $subMultiCurrency){
		       				SubMultiCurrency::findOrFail($subMultiCurrency->id)->delete();
		       			}
		       		}
		       	}

	       

	      }); // end transcation

	    return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);
	}

	public function edit($id){
		$data = SubCompetitor::with('subTechnicalNumber','subFinancialCost','subMultiCurrency')->find($id);
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
