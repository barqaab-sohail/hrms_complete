<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Submission\SubDocument;
use App\Models\Submission\SubDocumentContent;
use App\Helper\DocxConversion;
use DB;


class SubmissionDocumentController extends Controller
{
    
    public function show ($id){ 
        $documentIds = SubDocument::where('submission_id', session('submission_id'))->get();
        return view('submission.document.list',compact('documentIds'));
    }


    public function create(Request $request){
    	if($request->ajax()){
            return view ('submission.document.create');
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }
    }

    public function store(Request $request){

        $input = $request->all();

            
            if($request->filled('document_date')){
            $input ['document_date']= \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');
            }
 
    	DB::transaction(function () use ($request, $input) { 

    			$extension = request()->document->getClientOriginalExtension();            
                $fileName =strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s','', $input['description'])).'-'. time().'.'.$extension;
                $folderName = "submission/".session('submission_id')."/";
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
                $input['submission_id']=session('submission_id');
               
            $subDocument = SubDocument::create($input); 
            $input['sub_document_id'] = $subDocument->id;
            // $test = $input['content'];
            //$test = strlen($input['content']);
            if (strlen($input['content'])>50 && strlen($input['content'])<16777200){
                
                SubDocumentContent::create($input); 
            }

    	});  //end transaction

    	return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);
    }

    public function edit(Request $request, $id){

        $data = SubDocument::find($id);
       
        if($request->ajax()){
            return view ('submission.document.edit',compact('data'));
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }
    }

    public function update(Request $request, $id){
        $input = $request->all();
     
        $subDocument = SubDocument::findOrFail($id);

        DB::transaction(function () use ($request, $input, $id, $subDocument) { 
            //If document attached then first delete existing file and then new file save
            if ($request->hasFile('document')){
                
                //Delete Existing Document
                $path = public_path('storage/'.$subDocument->path.$subDocument->file_name);
                if(File::exists($path)){
                    File::delete($path);
                }

                //Now save new Document
                $extension = request()->document->getClientOriginalExtension();
                $fileName =strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s','', $input['description'])).'-'. time().'.'.$extension;
                $folderName = "submission/".session('submission_id')."/";
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
                $input['submission_id']=session('submission_id');

                
                //update project document content  
                if (strlen($input['content'])>50 && strlen($input['content'])<16777200){
                    SubDocumentContent::updateOrCreate(
                    ['sub_document_id'=> $id],       //It is find and update 
                    $input);  
                }                  

            }

            // Now update remaining inputs in pr_document
                $subDocument->update($input);

        

 
        });  //end transaction

        return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);

    }




    public function refreshTable(){
        $documentIds = SubDocument::where('submission_id', session('submission_id'))->get();
        return view('submission.document.list',compact('documentIds'));
    }



}
