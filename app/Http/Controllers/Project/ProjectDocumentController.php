<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrDocumentName;
use App\Helper\DocxConversion;
use DB;

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

        $input = $request->all();

            if ($request->filled('description')) {
            //
            }else{
                $input['description']= HrDocumentName::find($input['hr_document_name_id'])->name;
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

            //CvAttachment::create($attachment);


    	});  //end transaction

    	return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);

    }


}
