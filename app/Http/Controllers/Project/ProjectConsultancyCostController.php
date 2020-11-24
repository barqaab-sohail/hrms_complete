<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrCostType;
use App\Models\Project\PrDetail;
use App\Models\Project\PrConsultancyCost;
use App\Models\Project\PrConsultancyCostMm;
use App\Models\Project\PrConsultancyCostDirect;
use App\Models\Project\PrConsultancyCostTax;
use App\Models\Project\PrConsultancyCostContingency;
use App\Http\Requests\Project\PrConsultancyCostStore;
use App\Models\Common\Partner;
use DB;


class ProjectConsultancyCostController extends Controller
{
    

	public function create(){

		$prCostTypes = PrCostType::all();
		$partners = Partner::all();
		$prDetail = PrDetail::find(session('pr_detail_id'));

		$prConsultancyCosts = PrConsultancyCost::where('pr_detail_id',session('pr_detail_id'))->get();

		return view ('project.consultancyCost.create',compact('prCostTypes','partners','prDetail','prConsultancyCosts'));
	}

	public function store(PrConsultancyCostStore $request){

		$input = $request->all();
		$input['pr_detail_id']=session('pr_detail_id');
		
		if($request->filled('man_month_cost')){
            $input ['man_month_cost']= intval(str_replace( ',', '', $request->man_month_cost));
        }

        if($request->filled('direct_cost')){
            $input ['direct_cost']= intval(str_replace( ',', '', $request->direct_cost));
        }

        if($request->filled('tax_cost')){
            $input ['tax_cost']= intval(str_replace( ',', '', $request->tax_cost));
        }

        if($request->filled('contingency_cost')){
            $input ['contingency_cost']= intval(str_replace( ',', '', $request->contingency_cost));
        }

     	DB::transaction(function () use ($input, $request) {  

    		$prConsultancyCost = PrConsultancyCost::create($input);
    		$input ['pr_consultancy_cost_id'] = $prConsultancyCost->id;
    		PrConsultancyCostMm::create($input);

    		if($request->filled('direct_cost')){
    			PrConsultancyCostDirect::create($input);
    		}

    		if($request->filled('tax_cost')){
    			PrConsultancyCostTax::create($input);
    		}

    		if($request->filled('contingency_cost')){
    			PrConsultancyCostContingency::create($input);
    		}

    	}); // end transcation

		return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);
	}


	public function edit(Request $request, $id){

		$prCostTypes = PrCostType::all();
		$partners = Partner::all();
		$prDetail = PrDetail::find(session('pr_detail_id'));

		$prConsultancyCosts = PrConsultancyCost::where('pr_detail_id',session('pr_detail_id'))->get();

		$data = PrConsultancyCost::find($id);
		

    if($request->ajax()){      
            return view ('project.consultancyCost.edit', compact('prCostTypes','partners','prDetail','prConsultancyCosts','data'));    
        }else{
          return back()->withError('Please contact to administrator, SSE_JS');    
        }
    	        
    	

	}

	public function update(PrConsultancyCostStore $request, $id){

		$input = $request->all();
		
		if($request->filled('man_month_cost')){
            $input ['man_month_cost']= intval(str_replace( ',', '', $request->man_month_cost));
        }

        if($request->filled('direct_cost')){
            $input ['direct_cost']= intval(str_replace( ',', '', $request->direct_cost));
        }

        if($request->filled('tax_cost')){
            $input ['tax_cost']= intval(str_replace( ',', '', $request->tax_cost));
        }

        if($request->filled('contingency_cost')){
            $input ['contingency_cost']= intval(str_replace( ',', '', $request->contingency_cost));
        }

     	DB::transaction(function () use ($input, $request, $id) {  


    		PrConsultancyCost::findOrFail($id)->update($input);

    		$prConsultancyCostMm = PrConsultancyCostMm::where('pr_consultancy_cost_id',$id)->first();	
			PrConsultancyCostMm::findOrFail($prConsultancyCostMm->id)->update($input);

    		//direct cost update		
    		if($request->filled('direct_cost')){
				PrConsultancyCostDirect::updateOrCreate(
                       ['pr_consultancy_cost_id'=> $id],       //It is find and update 
                         $input);  
    		}else{
    			PrConsultancyCostDirect::where('pr_consultancy_cost_id',$id)->delete();
    		}

    		//tax cost update
    		if($request->filled('tax_cost')){
				PrConsultancyCostTax::updateOrCreate(
                       ['pr_consultancy_cost_id'=> $id],       //It is find and update 
                         $input);  
    		}else{
    			PrConsultancyCostTax::where('pr_consultancy_cost_id',$id)->delete();
    		}

    		// contingency cost update
    		if($request->filled('contingency_cost')){
    			PrConsultancyCostContingency::updateOrCreate(
                       ['pr_consultancy_cost_id'=> $id],       //It is find and update 
                         $input);  
    		}else{
    			PrConsultancyCostContingency::where('pr_consultancy_cost_id',$id)->delete();
    		}

    	}); // end transcation

		return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Updated"]);

	}


	public function destroy($id){

		PrConsultancyCost::findOrFail($id)->delete();
		return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Deleted"]);
		
	}

	public function refreshTable(){

    	$prConsultancyCosts = PrConsultancyCost::where('pr_detail_id',session('pr_detail_id'))->get();
        return view('project.consultancyCost.list',compact('prConsultancyCosts'));   
    }


}
