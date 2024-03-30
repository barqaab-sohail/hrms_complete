<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset\AsPurchaseCondition;
use App\Http\Requests\Asset\AsPurchaseStore;
use App\Models\Asset\AsPurchase;
use App\Models\Asset\Asset;
use DB;


class AsPurchaseController extends Controller
{
    

    public function edit(Request $request, $id){
    	
    	$asPurchaseConditions = AsPurchaseCondition::all();
    	$data = Asset::find($id);

		   if($request->ajax()){
	             $view =  view('asset.purchase.edit', compact('asPurchaseConditions','data'))->render();

            	return response()->json($view);

	        }else{
	            return back()->withError('Please contact to administrator, SSE_JS');
	        }
    }

    public function update(AsPurchaseStore $request, $id){

        //ensure client end is is not changed
        if($id != session('asset_id')){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }

        $input = $request->all();

        if($request->filled('purchase_date')){
            $input ['purchase_date']= \Carbon\Carbon::parse($request->purchase_date)->format('Y-m-d');
        }

        if($request->filled('purchase_cost')){
        	$input ['purchase_cost'] = (int)str_replace(',', '', $input['purchase_cost']);
        }

        DB::transaction(function () use ($input) {  

        	
            AsPurchase::updateOrCreate(['asset_id'=> session('asset_id')],
            	[
                'as_purchase_condition_id'=> $input['as_purchase_condition_id'],
                'purchase_cost'=> $input ['purchase_cost'],
                'purchase_date'=> $input['purchase_date']
                ]);

        }); // end transcation


        return response()->json(['status'=> 'OK', 'message' => "Purchase Data Successfully Updated"]);


    }




}
