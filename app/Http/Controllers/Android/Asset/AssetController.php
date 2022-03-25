<?php

namespace App\Http\Controllers\Android\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset\Asset;
use App\Models\Asset\AsSubClass;
use App\Models\Asset\AsClass;
use App\Models\Common\Client;
use App\Models\Asset\AsDocumentation;
use App\Models\Asset\AsOwnership;
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

    	$input= $request->all();
    	$input['asset_code'] = AssetController::asCode($request->as_sub_class_id);

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
            $input['asset_id'] =$asset->id;
            AsOwnership::create($input);

    	}); // end transcation

    	$result = "Asset Sucessfully Saved";
    	

    	return response ()->json($result, 200);
    }

    public function asCode($asSubClassId){

        $asSubClass = AsSubClass::find($asSubClassId);

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

    public function subClasses($classId){
    	//$class = AsClass::where('name', $className)->first();
    	$asSubClass	= AsSubClass::where('as_class_id',$classId)->select('id','name')->get();
    	
    	return response ()->json($asSubClass, 200);
    	
    }

    public function clients(){
    	$clients = Client::select('id','name')->get();
    	return response ()->json($clients, 200);
    }

}
