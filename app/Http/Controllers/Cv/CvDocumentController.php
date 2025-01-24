<?php

namespace App\Http\Controllers\Cv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Cv\CvAttachment;
use App\Models\Cv\CvDetail;
use App\Helper\DocxConversion;
use App\Http\Requests\Cv\DocumentStore;
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

    public function store (DocumentStore $request){

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
        	return response()->json(['status'=> 'OK', 'message' => "Document Successfully Saved"]);
    }

    public function edit(Request $request, $id){

    	$data = CvAttachment::find($id);
	
		if($request->ajax()){
            return view ('cv.document.edit',compact('data'));
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }    	
    }

    public function update(Request $request, $id){

        $input = CvAttachment::find($id);
        if ($request->hasFile('document')){
           
            DB::transaction(function () use ($request, $input, $id) { 
                $extension = request()->document->getClientOriginalExtension();
                $fileName =strtolower(request()->document_name).'-'. time().'.'.$extension;
                $folderName = $input->path;
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

                $attachment['document_name']=request()->document_name;
                $attachment['file_name']=$fileName;
                $attachment['size']=$request->file('document')->getSize();
                $attachment['extension']=$extension;

                CvAttachment::findOrFail($id)->update($attachment);
                
                if(File::exists(public_path('storage/'.$input->path.$input->file_name))){
                    File::delete(public_path('storage/'.$input->path.$input->file_name));
                }

            });  //end transaction


        }else{

            DB::transaction(function () use ($request, $id) {  
            CvAttachment::findOrFail($id)->update(['document_name'=>$request->document_name]);
            }); // end transcation

        }
        return response()->json(['status'=> 'OK', 'message' => "Document Successfully Updated"]);


    }

    public function refreshTable(){
        $documentIds = CvAttachment::where('cv_detail_id', session('cv_detail_id'))->get();
        return view('cv.document.list',compact('documentIds'));
    }

    public function destroy($id){

    	$cvDocument = CvAttachment::findOrFail($id);
            $path = public_path('storage/'.$cvDocument->path.$cvDocument->file_name);
            if(File::exists($path)){
                File::delete($path);
            }
        $cvDocument->forceDelete();
        return response()->json(['status'=> 'OK', 'message' => "Data Successfully Deleted"]);

    }



}
