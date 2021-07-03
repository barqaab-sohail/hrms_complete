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
use App\Models\Asset\AsDocumentation;
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

    public function store (AssetStore $request){

    	$input = $request->all();
    	  
        $asset='';
    	DB::transaction(function () use ($input, $request, &$asset) {  
            $today = \Carbon\Carbon::today();

            $asset=Asset::create($input);
            
            //add image
                $extension = request()->document->getClientOriginalExtension();
                $fileName = time().'.'.$extension;
                $folderName = "asset/".$asset->id."/";
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


    public function update (AssetStore $request, $id){

         $input = $request->all();

        DB::transaction(function () use ($input, $request, $id) {  
            $today = \Carbon\Carbon::today();
            
            Asset::findOrFail($id)->update($input);

            //Edit image
            if ($request->hasFile('document')){

                $extension = request()->document->getClientOriginalExtension();
                $fileName = time().'.'.$extension;
                $folderName = "asset/".$id."/";
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
