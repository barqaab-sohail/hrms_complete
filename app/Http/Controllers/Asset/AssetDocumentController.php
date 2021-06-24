<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset\AsDocumentation;
use DataTables;
use DB;

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
                           return '<img  id="ViewIMG" src="'.$url.'" width=50/>';
                           
                    })
                    ->rawColumns(['Edit','Delete','document'])
                    ->make(true);
          
        }
        
        $view =  view('asset.document.create')->render();
        return response()->json($view);

    }

    public function store (Request $request){

        $input = $request->all();

         DB::transaction(function () use ($input, $request) {  

             //add image
                $extension = request()->document->getClientOriginalExtension();
                $fileName = time().'.'.$extension;
                $folderName = "asset/".strtolower('2')."/";
                //store file
                $request->file('document')->storeAs('public/'.$folderName,$fileName);
                
                $file_path = storage_path('app/public/'.$folderName.$fileName);

                $attachment['description']='image';
                $attachment['file_name']=$fileName;
                $attachment['size']=$request->file('document')->getSize();
                $attachment['path']=$folderName;
                $attachment['extension']=$extension;
                $attachment['asset_id']=$asset->id;

            AsDocumentation::updateOrCreate(['id' => $input['as_document_id']],
                $attachment); 
           
        }); // end transcation      
       return response()->json(['success'=>'Data saved successfully.']);
    }

    public function edit($id)
    {
        $document = AsDocumentation::find($id);
        return response()->json($document);
    }


}
