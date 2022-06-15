<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Office\Office;
use App\Http\Requests\Asset\AssetStore;
use App\Http\Requests\Asset\ClassStore;
use App\Http\Requests\Asset\SubClassStore;
use App\Models\Asset\Asset;
use App\Models\Asset\AsClass;
use App\Models\Asset\AsSubClass;
use App\Models\Asset\AsDocumentation;
use DB;
use Storage;
use DataTables;

class AssetController extends Controller
{
    
    public function index(Request $request){
        
        $assets = Asset::join('audits','audits.auditable_id','assets.id')->select('assets.*', 'audits.user_id','audits.auditable_id','audits.auditable_type')->where('auditable_type','App\Models\Asset\Asset')->where('user_id', Auth::user()->id)->with('asCurrentLocation','asCurrentAllocation','asDocumentation')->get(); 
         if($request->ajax()){

            $data = Asset::join('audits','audits.auditable_id','assets.id')->select('assets.*', 'audits.user_id','audits.auditable_id','audits.auditable_type')->where('auditable_type','App\Models\Asset\Asset')->where('user_id', Auth::user()->id)->distinct()->with('asCurrentLocation','asCurrentAllocation','asDocumentation')->get();

            if(Auth::user()->can('asset all record')){
                $data = Asset::with('asCurrentLocation','asCurrentAllocation','asDocumentation')->get();
            }
                       
           
            return DataTables::of($data)
            ->addColumn('location', function($data){
            
                $location = $data->asCurrentLocation->name??'';
                $allocation = $data->asCurrentAllocation->full_name??'';
                if($location){
                    return $location;
                }elseif($allocation){
                    return $allocation .' - '. $data->asCurrentAllocation->designation??'';
                }else{
                    return 'N/A';
                }
                 
            })
            ->addColumn('bar_code', function($data){
                //$barCode ='<img src="data:image/png;base64,'.\DNS1D::getBarcodePNG($data->asset_code,'C39+',1,33,array(0,0,0),true).'" alt="barcode" />';


                $qrCode = '<img  src="data:image/png;base64,' . \DNS2D::getBarcodePNG($data->asset_code, 'QRCODE') . '" alt="barcode"   /><br><p style="color:black; font-weight: bold">'.$data->asset_code.'</p>';

                return $qrCode;
            })
            ->addColumn('image', function($data){
                if ($data->asDocumentation->extension != 'pdf'){
               $image ='<img src="'.url(isset($data->asDocumentation->file_name)?'/storage/'.$data->asDocumentation->path.$data->asDocumentation->file_name:'Massets/images/document.png').'" class="img-round picture-container picture-src"  id="ViewIMG'.$data->id.'" width=50>';
                }else{
                     $image ='<img src="'.asset('Massets/images/document.png').'" href="'.url(isset($data->asDocumentation->file_name)?'/storage/'.$data->asDocumentation->path.$data->asDocumentation->file_name:'Massets/images/document.png').'" class="img-round picture-container picture-src"  id="ViewPDF'.$data->id.'" width=50>';
                }


                return $image;
            })
           
            ->addColumn('edit', function($data){
       
            $button = '<a class="btn btn-success btn-sm" href="'.route('asset.edit',$data->id).'"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>';

            return $button;  

            })
            ->addColumn('delete', function($data){

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteAsset">Delete</a>';                            
                    return $btn;

                    })
            ->rawColumns(['location','bar_code','image','edit','delete'])
            ->make(true);
        }
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
                $folderName = "asset/".  $asset->id."/";
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
        DB::transaction(function () use ($id) {  
            $asDocuments = AsDocumentation::where('asset_id',$id)->get();
            foreach ($asDocuments as $asDocument) {
                $path = public_path('storage/'.$asDocument->path.$asDocument->file_name);
                    if(File::exists($path)){
                      File::delete($path);
                    }  
            }
            Asset::findOrFail($id)->delete();   
        }); // end transcation

        return response()->json(['success'=>'data  delete successfully.']);
   
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

     public function storeClass (ClassStore $request){
        $newClass = preg_replace('/[^A-Za-z0-9\- ]/', '', $request->name);
        $class = AsClass::where('name', $newClass)->first();
       
        if($class == null){
            
             DB::transaction(function () use ($request, $newClass) {  

                 AsClass::create(['name'=>$newClass]);

            }); // end transcation   

            $classes = DB::table("as_classes")->orderBy('id')
                ->pluck("id","name");
        
            return response()->json(['classes'=> $classes, 'message'=>"$newClass Successfully Entered"]);
        }else{

            return response()->json(['classes'=> '', 'message'=>"$newClass is already entered"]);
           
        }
    }

     public function storeSubClass (SubClassStore $request){
        $newSubClass = preg_replace('/[^A-Za-z0-9\- ]/', '', $request->name);
        $subClass = AsSubClass::where('as_class_id',$request->as_class_id)->where('name', $newSubClass)->first();
       
        if($subClass == null){
            
             DB::transaction(function () use ($request, $newSubClass) {  

                 AsSubClass::create(['name'=>$newSubClass, 'as_class_id'=>$request->as_class_id]);

            }); // end transcation   

            $subClasses = DB::table("as_sub_classes")->where('as_class_id',$request->as_class_id)->orderBy('id')
                ->pluck("id","name");
        
            return response()->json(['subClasses'=> $subClasses, 'message'=>"$newSubClass Successfully Entered"]);
        }else{

            return response()->json(['subClasses'=> '', 'message'=>"$newSubClass is already entered"]);
           
        }
      
        
    }

    public function search(){
        $offices = Office::all();
        return view ('asset.search.search',compact('offices'));
    }

    public function result(Request $request){
        $data = $request->all();

        $result = Asset::join('as_locations','as_locations.asset_id','=','assets.id')
                        // ->join('cv_detail_education','cv_detail_education.cv_detail_id','=','cv_details.id')
                            ->when($data['office_id'], function ($query) use ($data){
                                return $query->where('office_id','=',$data['office_id']);
                            })
                            // ->when($data['stage_id'], function ($query) use ($data){
                            //     return $query->where('cv_stage_id','=',$data['stage_id']);
                            // })
                            
                        ->select('assets.*')
                        //->distinct('id')
                        ->get();
        return view('asset.search.result',compact('result'));
    }

}
