<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Requests\Asset\AssetStore;
use App\Models\Asset\Asset;
use App\Models\Asset\AsClass;
use App\Models\Asset\AsSubClass;
use App\Models\Asset\AsConditionType;
use App\Models\Asset\AsCondition;
use App\Models\Asset\AsPurchaseCondition;
use App\Models\Asset\AsPurchase;
use App\Models\Asset\AsOwnership;
use App\Models\Asset\AsLocation;
use App\Models\Asset\AsAllocation;
use App\Models\Asset\AsDocumentation;
use App\Models\Common\Client;
use App\Models\Office\Office;
use App\Models\Hr\HrEmployee;
use DB;

class AssetController extends Controller
{
    
    public function index(){
        $assets = Asset::all();
        return view ('asset.index', compact('assets'));
    }

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

    	DB::transaction(function () use ($input, $request) {  

            $asset=Asset::create($input);
            AsCondition::create([
                'asset_id'=>$asset->id,
                'as_condition_type_id'=> $input['as_condition_type_id'],
                'date'=> $input['purchase_date']
                ]);
            $purchaseCost = (int)str_replace(',', '', $input['purchase_cost']);
            AsPurchase::create([
                'asset_id'=>$asset->id,
                'as_purchase_condition_id'=> $input['as_purchase_condition_id'],
                'purchase_cost'=> $purchaseCost,
                'purchase_date'=> $input['purchase_date']
                ]);
            AsOwnership::create([
                'asset_id'=>$asset->id,
                'client_id'=> $input['client_id'],
                'date'=> $input['purchase_date']
                ]);
            if($request->filled('hr_employele_id')){
            AsAllocation::create([
                'asset_id'=>$asset->id,
                'hr_employele_id'=> $input['hr_employele_id'],
                'date'=> $input['purchase_date']
                ]);  
            }
            if($request->filled('office_id')){
            AsLocation::create([
                'asset_id'=>$asset->id,
                'office_id'=> $input['office_id'],
                'date'=> $input['purchase_date']
                ]);  
            }

            //add image
                $extension = request()->document->getClientOriginalExtension();
                $fileName = time().'.'.$extension;
                $folderName = "asset/".strtolower(request()->as_class_id)."/";
                //store file
                $request->file('document')->storeAs('public/'.$folderName,$fileName);
                
                $file_path = storage_path('app/public/'.$folderName.$fileName);

                $attachment['description']='image';
                $attachment['file_name']=$fileName;
                $attachment['size']=$request->file('document')->getSize();
                $attachment['path']=$folderName;
                $attachment['extension']=$extension;
                $attachment['asset_id']=$asset->id;

            AsDocumentation::create($attachment);
    		
    	}); // end transcation

    	return response()->json(['url'=> route("asset.index"),'message' => 'Data Successfully Saved']);
    }




     public function destroy($id)
    {
        $asDocuments = AsDocumentation::where('asset_id',$id)->get();
        foreach ($asDocuments as $asDocument) {
            $path = public_path('storage/'.$asDocument->path.$asDocument->file_name);
                if(File::exists($path)){
                  File::delete($path);
                }  
        }
        Asset::findOrFail($id)->delete();   

    return back()->with('success', "Data successfully deleted");
   
    }

    public function getSubClasses($id){

    	$as_sub_classes = DB::table("as_sub_classes")
	                ->where("as_class_id",$id)
	                ->pluck("name","id");
	    
	    return response()->json($as_sub_classes);


    }
}
