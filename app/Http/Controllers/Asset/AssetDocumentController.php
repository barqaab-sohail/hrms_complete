<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset\AsDocumentation;
use DataTables;

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


}
