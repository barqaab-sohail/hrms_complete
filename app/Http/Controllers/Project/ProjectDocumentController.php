<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrDocumentName;
use App\Helper\DocxConversion;
use DB;
use Storage;

class ProjectDocumentController extends Controller
{
    

    public function create(Request $request){

    	$documentNames = HrDocumentName::all();

        if($request->ajax()){
            $view = view ('project.document.create',compact('documentNames'))->render();
            return response()->json($view);
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }


    }


    public function store(Request $request){

   //



    	DB::transaction(function () use ($request, &$disk) { 

    			$disk = Storage::disk('local');
				// $disk->put($targetFile, fopen($sourceFile, 'r+'));


    	});  //end transaction

    	return response()->json(['status'=> 'OK', 'message' => "$disk - Data Sucessfully Saved"]);

    }


}
