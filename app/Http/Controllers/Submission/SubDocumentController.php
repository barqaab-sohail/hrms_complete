<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Submission\Submission;
use App\Models\Submission\SubDocument;
use App\Models\Submission\SubDocumentContent;
use App\Helper\DocxConversion;
use App\Http\Requests\Submission\SubDocumentStore;
use DataTables;
use DB;
use Storage;

class SubDocumentController extends Controller
{
    
   
	public function index(){
    	
       $view =  view('submission.document.create')->render();
        return response()->json($view);
    }

    public function create(Request $request){

    	if ($request->ajax()) {
            $data = SubDocument::where('submission_id',session('submission_id'))->latest()->get();

            return  DataTables::of($data)
                    
                    ->addColumn('Edit', function($data){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editDocument">Edit</a>';
                                                     
                            return $btn;
                    })
                    ->addColumn('Delete', function($data){                
                      
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteDocument">Delete</a>';
                            
                           
                            return $btn;
                    })
                    ->addColumn('document', function($data){                
                        if ($data->extension == 'jpeg' || $data->extension == 'png' || $data->extension == 'jpg'){
                         $image ='<img src="'.url(isset($data->file_name)?'/storage/'.$data->path.$data->file_name:'Massets/images/document.png').'" class="img-round picture-container picture-src viewImg"  id="ViewIMG'.$data->id.'" width=50>';
                        }elseif($data->extension == 'pdf'){
                             $image ='<img src="'.asset('Massets/images/document.png').'" href="'.url(isset($data->file_name)?'/storage/'.$data->path.$data->file_name:'Massets/images/document.png').'" class="img-round picture-container picture-src viewPdf"  id="ViewPDF'.$data->id.'" width=50>';
                        }else{
                            $image = '<a src="'.asset('Massets/images/document.png').'" href="'.url(isset($data->file_name)?'/storage/'.$data->path.$data->file_name:'Massets/images/document.png').'" class="img-round picture-container picture-src viewDoc"  id="ViewDOC'.$data->id.'" width=50><i class="fa fa-download" aria-hidden="true"></i>Download</a>';
                        }

                    return $image;
                        // $url= asset('storage/'.$row->path.$row->file_name);
                        // $pdfMono = asset('Massets/images/document.png');
                        // if($row->extension == 'pdf'){
                        //     return '<img  id="ViewPDF" src="'.$pdfMono.'" href="'.$url.'" width=30/>';
                        // }else{
                        //     return '<img  id="ViewIMG" src="'.$url.'" width=30/>';
                        // }
                        
                           
                    })
                    ->rawColumns(['Edit','Delete','document'])
                    ->make(true);
          
        }
        
        $view =  view('submission.document.create')->render();
        return response()->json($view);

    }

    public function store (SubDocumentStore $request){
        $submission = Submission::find(session('submission_id'));

        $input = $request->all();

         DB::transaction(function () use ($input, $request, $submission) {  

             //add image
            if ($request->hasFile('document')){
                $extension = request()->document->getClientOriginalExtension();
                $fileName = time().'.'.$extension;
                $folderName = "submission/".$submission->submission_no."/";
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

                $attachment['file_name']=$fileName;
                $attachment['size']=$request->file('document')->getSize();
                $attachment['path']=$folderName;
                $attachment['extension']=$extension;

                $attachment['submission_id']=session('submission_id');
                $attachment['description']=$input['description'];

                $subDocument = SubDocument::where('id',request()->sub_document_id)->first();

                if($subDocument){
                    $oldDocumentPath =  $subDocument->path.$subDocument->file_name;
                    SubDocument::findOrFail($subDocument->id)->update($attachment);

                    if(File::exists(public_path('storage/'.$oldDocumentPath))){
                        File::delete(public_path('storage/'.$oldDocumentPath));
                    }
                    
                }else{
                    SubDocument::create($attachment);
                }
            }
            
            if($input['sub_document_id']){

                 SubDocument::findOrFail($input['sub_document_id'])->update($input);

            }

                
           
        }); // end transcation     

       return response()->json(['success'=>'Data saved successfully.']);
    }

    public function edit($id)
    {
        $document = SubDocument::find($id);
        return response()->json($document);
    }

    public function destroy ($id){
       
        DB::transaction(function () use ($id) {
        	$subDocument = SubDocument::where('id',$id)->first();
         	$subDocumentPath =  $subDocument->path.$subDocument->file_name;
            $subDocument->delete(); 

            if(File::exists(public_path('storage/'.$subDocumentPath))){
                        File::delete(public_path('storage/'.$subDocumentPath));
            }         
        }); // end transcation 
        return response()->json(['success'=>'data  delete successfully.']);

    }


}
