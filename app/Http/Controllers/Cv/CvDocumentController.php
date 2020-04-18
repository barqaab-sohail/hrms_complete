<?php

namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CV\CvAttachment;
use App\Models\CV\CvDetail;
use App\Helper\DocxConversion;
use DB;

class CvDocumentController extends Controller
{
    

    public function create(Request $request){


    	if($request->ajax()){
            return view ('cv.document.create');
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }

    }

    public function store (Request $request){

        	$cvDetail= CvDetail::find(session('cv_detail_id'));
        	$folderName= $cvDetail->cvAttachment->first()->path;
        	//start transaction
			DB::transaction(function () use ($request, $folderName, $cvDetail) {   
				$extension = request()->document->getClientOriginalExtension();
				$fileName =strtolower(request()->description).'-'. time().'.'.$extension;
				//store file
				$request->file('document')->storeAs('public/'.$folderName,$fileName);
				
				$file_path = storage_path('app/public/'.$folderName.$fileName);
			
				$attachment['content']='';
											
					if (($extension == 'doc')||($extension == 'docx')){
						$text = new DocxConversion($file_path);
						$attachment['content']=mb_strtolower($text->convertToText());
					}else if ($extension =='pdf'){
						$reader = new \Asika\Pdf2text;
						$attachment['content'] = mb_strtolower($reader->decode($file_path));
					}

				$attachment['document_name']=request()->description;
				$attachment['file_name']=$fileName;
				$attachment['size']=$request->file('document')->getSize();
				$attachment['path']=$folderName;
				$attachment['extension']=$extension;
				$attachment['cv_detail_id']=$cvDetail->id;
			CvAttachment::create($attachment);


		});  //end transaction
        	return response()->json(['status'=> 'OK', 'message' => "Document Sucessfully Saved"]);
    }

     public function refreshTable(){
        $documentIds = CvAttachment::where('cv_detail_id', session('cv_detail_id'))->get();
        return view('cv.document.list',compact('documentIds'));
    }



}
