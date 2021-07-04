<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Asset\AsDocumentation;
use App\Models\Asset\Asset;
use App\Http\Requests\Asset\AsDocumentStore;
use DataTables;
use DB;
use Storage;

class AssetDocumentController extends Controller
{
    
   
	public function index(){
    	
       $view =  view('asset.document.create')->render();
        return response()->json($view);
    }

    public function create(Request $request){

    	if ($request->ajax()) {
            $data = AsDocumentation::where('asset_id',session('asset_id'))->latest()->get();

            return  DataTables::of($data)
                    ->addIndexColumn()
                    
                    ->addColumn('Edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editDocument">Edit</a>';
                                                     
                            return $btn;
                    })
                    ->addColumn('Delete', function($row){                
                      
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteDocument">Delete</a>';
                            
                           
                            return $btn;
                    })
                    ->addColumn('document', function($row){                
                        
                        $url= asset('storage/'.$row->path.$row->file_name);
                        $pdfMono = asset('Massets/images/document.png');
                        if($row->extension == 'pdf'){
                            return '<img  id="ViewPDF" src="'.$pdfMono.'" href="'.$url.'" width=30/>';
                        }else{
                            return '<img  id="ViewIMG" src="'.$url.'" width=30/>';
                        }
                        
                           
                    })
                    ->rawColumns(['Edit','Delete','document'])
                    ->make(true);
          
        }
        
        $view =  view('asset.document.create')->render();
        return response()->json($view);

    }

    public function store (AsDocumentStore $request){
        $asset = Asset::where('id',session('asset_id'))->first();

        $input = $request->all();

         DB::transaction(function () use ($input, $request, $asset) {  

             //add image
            if ($request->hasFile('document')){
                $extension = request()->document->getClientOriginalExtension();
                $fileName = time().'.'.$extension;
                $folderName = "asset/".$asset->id."/";
                //store file
                $request->file('document')->storeAs('public/'.$folderName,$fileName);
                
                $file_path = storage_path('app/public/'.$folderName.$fileName);
                $attachment['file_name']=$fileName;
                $attachment['size']=$request->file('document')->getSize();
                $attachment['path']=$folderName;
                $attachment['extension']=$extension;

                $attachment['asset_id']=session('asset_id');
                $attachment['description']=$input['description'];

                $asDocumentation = AsDocumentation::where('id',request()->as_document_id)->first();

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
            
            if($input['as_document_id']){

                 AsDocumentation::findOrFail($input['as_document_id'])->update($input);

            }

                
           
        }); // end transcation     

       return response()->json(['success'=>'Data saved successfully.']);
    }

    public function edit($id)
    {
        $document = AsDocumentation::find($id);
        return response()->json($document);
    }

    public function destroy ($id){
        $documentImage = AsDocumentation::where('id',$id)->first();
        if(AsDocumentation::where('asset_id',session('asset_id'))->count()<2 || strtolower($documentImage->description) =='image')
        {
            return response()->json(['error'=>'You cannot delete asset image']);
        }

        DB::transaction(function () use ($id) {
        	$asDocumentation = AsDocumentation::where('id',$id)->first();
         	$asDocumentPath =  $asDocumentation->path.$asDocumentation->file_name;
            $asDocumentation->delete(); 

            if(File::exists(public_path('storage/'.$asDocumentPath))){
                        File::delete(public_path('storage/'.$asDocumentPath));
                    }         
        }); // end transcation 
        return response()->json(['success'=>'data  delete successfully.']);

    }


}
