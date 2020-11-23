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


	public function destroy($id){

		PrConsultancyCost::findOrFail($id)->delete();
		return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Deleted"]);
		
	}

	public function refreshTable(){

    	$prConsultancyCosts = PrConsultancyCost::where('pr_detail_id',session('pr_detail_id'))->get();
        return view('project.consultancyCost.list',compact('prConsultancyCosts'));   
    }


}
