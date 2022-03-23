<?php

namespace App\Http\Controllers\Android\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset\Asset;
use App\Models\Asset\AsSubClass;
use App\Models\Asset\AsClass;
use App\Models\Asset\AsDocumentation;
use DB;


class AssetController extends Controller
{
    public function show($id){

    	$asset = Asset::where('asset_code',$id)->select('description','id','as_sub_class_id','asset_code')->first();
    	//$asset = Asset::find($id);
    	$image = AsDocumentation::where('asset_id', $id)->where('description','image')->first();

    	$data['description'] = $asset->description;
    	$data['asset_code'] = $asset->asset_code;
    	$data['asset_image'] = asset('/storage/'.$image->path . $image->file_name);
    	$data['allocation'] = $asset->asCurrentAllocation->full_name??'';
    	$data['location'] = $asset->asCurrentLocation->name??'';
    	$data ['ownsership']=$asset->asOwnership->name??'';

    	return response()->json($data, 202);
    }

    public function store (Request $request){

    	$asSubClass = AsSubClass::where('name', $request->as_sub_class_name)->first();
    	$input= $request->all();
    	$input['as_sub_class_id']= $asSubClass->id;
    	$assetCode = AssetController::asCode($input['as_sub_class_id']);
    	$input['asset_code'] = $assetCode;

    	DB::transaction(function () use ($input, $request, &$asset) { 
    		$asset = Asset::create($input);

    		$fileName = time().'.'.'jpg';
    		$folderName = storage_path('app/public/asset/'.$asset->id);
    		
    		//check if folder not exist than create folder
    		if(!\File::isDirectory($folderName)){
                    \File::makeDirectory($folderName, 0777, true, true);
                   //mkdir($folderName);
            }
    		
    		//save image to folder
    		$image = base64_decode($request->En_Image);

    		\File::put($folderName. '/' . $fileName, $image);

    		// check variable size
	        $serializedFoo = serialize($image);
	        $size = mb_strlen($serializedFoo, '8bit');
	        

    		$attachment['description']='image';
            $attachment['file_name']=$fileName;
            $attachment['size']=$size;
            $attachment['path']='asset/'.$asset->id.'/';
            $attachment['extension']='jpg';
            $attachment['asset_id']=$asset->id;

            AsDocumentation::create($attachment);

    	}); // end transcation

    	$result = "Asset Sucessfully Saved";
    	

    	return response ()->json($result, 200);
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

        return $asCode;
    }

    public function classes(){
    	
    	$asClasses = AsClass::select('id','name')->orderBy('id', 'asc')->get();

    	return response ()->json($asClasses, 200);
    	
    }

    public function subClasses($className){
    	$class = AsClass::where('name', $className)->first();
    	$asSubClass	= AsSubClass::where('as_class_id',$class->id)->select('id','name')->get();
    	
    	return response ()->json($asSubClass, 200);
    	
    }

}
