<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrDocumentName;
use App\Models\Project\PrDocument;
use App\Helper\DocxConversion;
use DB;

class ProjectDocumentController extends Controller
{
    

    public function create(Request $request){

    	$documentNames = PrDocumentName::all();

        if($request->ajax()){
            $view = view ('project.document.create',compact('documentNames'))->render();
            return response()->json($view);
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }


    }


    public function store(Request $request){

        $input = $request->all();

            if ($request->filled('description')) {
            //
            }else{
                $input['description']= PrDocumentName::find($input['pr_document_name_id'])->name;
            }

            if($request->filled('document_date')){
            $input ['document_date']= \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');
            }

 
    	DB::transaction(function () use ($request, $input) { 

    			$extension = request()->document->getClientOriginalExtension();
                $fileName =strtolower($input['description']).'-'. time().'.'.$extension;
                $folderName = "project/".session('pr_detail_id')."/";
                //store file
                $request->file('document')->storeAs('public/'.$folderName,$fileName);
                
                $file_path = storage_path('app/public/'.$folderName.$fileName);
            
                $input['content']='';
                                            
                    if (($extension == 'doc')||($extension == 'docx')){
                        $text = new DocxConversion($file_path);
                        $input['content']=mb_strtolower($text->convertToText());
                    }else if ($extension =='pdf'){
                        $reader = new \Asika\Pdf2text;
                        $input['content'] = mb_strtolower($reader->decode($file_path));
                    }

                
                $input['file_name']=$fileName;
                $input['size']=$request->file('document')->getSize();
                $input['path']=$folderName;
                $input['extension']=$extension;
                $input['pr_detail_id']=session('pr_detail_id');
               
            $prDocument = PrDocument::create($input);  

            if($request->pr_document_name_id!='Other'){
            $prDocumentNameId = $request->input("pr_document_name_id");

            //pr_detail_id is add due to validtaion before enter into database
            $prDocument->prDocumentName()->attach($prDocumentNameId, ['pr_detail_id'=>session('pr_detail_id')]);   
            }   



    	});  //end transaction

    	return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);

    }


}
