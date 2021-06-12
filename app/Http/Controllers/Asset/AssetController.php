<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset\AsClass;
use App\Models\Asset\AsSubClass;
use App\Models\Asset\AsConditionType;
use App\Models\Asset\AsPurchaseCondition;
use App\Models\Common\Client;
use App\Models\Office\Office;
use App\Models\Hr\HrEmployee;


use DB;

class AssetController extends Controller
{
    

    public function create(){
    	session()->put('asset_id', '');
    	$asClasses = AsClass::all();
    	$asSubClasses = AsSubClass::all();
    	$asConditionTypes = AsConditionType::all();
    	$asPurchaseConditions = AsPurchaseCondition::all();
    	$asOwnerships = Client::all();
    	$offices = Office::all();
    	$employees = HrEmployee::all();
        
    	return view ('asset.create', compact('asClasses','asSubClasses','asConditionTypes','asPurchaseConditions','asOwnerships','offices','employees'));
    }

    public function store (Request $request){

    	 $input = $request->all();
    	  if($request->filled('purchase_date')){
            $input ['purchase_date']= \Carbon\Carbon::parse($request->purchase_date)->format('Y-m-d');
            }

    	DB::transaction(function () use ($input) {  

    		

    	}); // end transcation

    	return response()->json(['url'=> route("asset.create"),'message' => 'Data Successfully Saved']);
    }

    public function getSubClasses($id){

    	$as_sub_classes = DB::table("as_sub_classes")
	                ->where("as_class_id",$id)
	                ->pluck("name","id");
	    
	    return response()->json($as_sub_classes);


    }
}
