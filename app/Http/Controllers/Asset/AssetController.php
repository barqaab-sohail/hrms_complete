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
use Storage;

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
        
    	return view ('asset.create', compact('asClasses','asSubClasses'));
    }

    public function store (Request $request){

    	$input = $request->all();
    	  
        $asset='';
    	DB::transaction(function () use ($input, $request, &$asset) {  
            $today = \Carbon\Carbon::today();

            $asset=Asset::create($input);
            // AsCondition::create([
            //     'asset_id'=>$asset->id,
            //     'as_condition_type_id'=> $input['as_condition_type_id'],
            //     'date'=>  $today
            //     ]);
         
            // AsOwnership::create([
            //     'asset_id'=>$asset->id,
            //     'client_id'=> $input['client_id'],
            //     'date'=>  $today
            //     ]);
            // if($request->filled('hr_employele_id')){
            // AsAllocation::create([
            //     'asset_id'=>$asset->id,
            //     'hr_employele_id'=> $input['hr_employele_id'],
            //     'date'=>  $today
            //     ]);  
            // }
            // if($request->filled('office_id')){
            // AsLocation::create([
            //     'asset_id'=>$asset->id,
            //     'office_id'=> $input['office_id'],
            //     'date'=>  $today
            //     ]);  
            // }

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

    	return response()->json(['url'=> route("asset.edit",$asset),'message' => 'Data Successfully Saved']);
    }

    public function edit(Request $request, $id){
    	session()->put('asset_id', $id);
    	$asClasses = AsClass::all();
    	$asSubClasses = AsSubClass::all();
    	$data = Asset::find($id);

        if($request->ajax()){      
            return view ('asset.ajax', compact('asClasses','asSubClasses','data'));   
        }else{
            return view ('asset.edit', compact('asClasses','asSubClasses','data'));       
        }

    	


    }


    public function update (Request $request, $id){

         $input = $request->all();

        DB::transaction(function () use ($input, $request, $id) {  
            $today = \Carbon\Carbon::today();
            
            Asset::findOrFail($id)->update($input);

            // $asCondition = AsCondition::where('asset_id',$id)->first(); 
            // AsCondition::findOrFail($asCondition->id)->update([
            //     'as_condition_type_id'=> $input['as_condition_type_id'],
            //     'date'=>  $today
            //     ]);



            // $asOwnership = AsOwnership::where('asset_id',$id)->first(); 
            // AsOwnership::findOrFail($asOwnership->id)->update([
            //     'client_id'=> $input['client_id'],
            //     'date'=>  $today
            //     ]);

            // if($request->filled('hr_employele_id')){

            // $asAllocation = AsAllocation::where('asset_id',$id)->first(); 
            // AsAllocation::findOrFail($asAllocation->id)->update([
            //     'hr_employele_id'=> $input['hr_employele_id'],
            //     'date'=>  $today
            //     ]);  
            // }
            // if($request->filled('office_id')){

            // $asLocation = AsLocation::where('asset_id',$id)->first();    
            // AsLocation::findOrFail($asLocation->id)->update([
            //     'office_id'=> $input['office_id'],
            //     'date'=>  $today
            //     ]);  
            // }

            //Edit image
            if ($request->hasFile('document')){

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
                $attachment['asset_id']=$id;

                $asDocumentation = AsDocumentation::where('asset_id',$id)->first();

                if($asDocumentation){
                    $oldDocumentPath =  $asDocumentation->path.$asDocumentation->file_name;
                    AsDocumentation::findOrFail($asDocumentation->id)->update($attachment);

                    if(File::exists(public_path('storage/'.$oldDocumentPath))){
                        File::delete(public_path('storage/'.$oldDocumentPath));
                    }
                    
                }else{
                    AsDocumentation::create($attachment);
                }
               
            }
            
        }); // end transcation

        return response()->json(['status'=> 'OK', 'message' => "Data Successfully Updated"]);

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

    public function asCode($asSubClass){

        $asSubClass = AsSubClass::where('id',$asSubClass)->first();


        $count = 1;
       // $code = $code.'0'; //200
        $asCode =  $asSubClass->as_class_id.'-'. $asSubClass->id.'-';
       // $asCode = $asCode.$count;

        while(Asset::where('asset_code',$asCode.$count)->count()>0){ 
            $count++;  
        }
        $asCode = $asCode.$count;

        return response()->json([ 'assetCode'=>$asCode]);
    }
}
