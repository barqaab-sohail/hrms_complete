<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Project\DocumentStore;
use App\Models\Project\PrDocumentName;
use App\Models\Project\PrDocument;
use App\Models\Project\PrDocumentPrDocumentName;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDocumentation;
use App\Helper\DocxConversion;
use DB;

class ProjectDocumentController extends Controller
{   
    

    public function create(Request $request){

    	$documentNames = PrDocumentName::all();
        $documentIds = PrDocument::where('pr_detail_id', session('pr_detail_id'))->get();

        $employees = HrEmployee::where('hr_status_id',1)->get();

        if($request->ajax()){
            $view = view ('project.document.create',compact('documentNames','documentIds','employees'))->render();
            return response()->json($view);
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }


    }


    public function store(DocumentStore $request){

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

            //add document path into employee record    
            if($request->filled("hr_employee_id.0")){
                for ($i=0;$i<count($request->input('hr_employee_id'));$i++){
                $input['hr_employee_id']=$request->input("hr_employee_id.$i");
                $input['pr_document_id']=$prDocument->id;
                HrDocumentation::create($input);       
                }
            }



    	});  //end transaction

    	return response()->json(['status'=> 'OK', 'message' => " Data Sucessfully Saved"]);

    }


    public function edit(Request $request, $id){


        $employees = HrEmployee::where('hr_status_id',1)->get();
        
        $employeeDocuments = HrDocumentation::where('pr_document_id',$id)->get()->pluck('hr_employee_id')->toArray();
        $documentNames = PrDocumentName::all();

        $data = PrDocument::find($id);
        $documentNameExist = PrDocumentPrDocumentName::where('pr_document_id',$data->id)->first();


        if($request->ajax()){
            return view ('project.document.edit',compact('documentNames','data','documentNameExist','employees','employeeDocuments'));
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }


    }


    public function update(Request $request, $id){

        $input = $request->all();
        $data = PrDocument::find($id);

        if($request->filled('document_date')){
            $input ['document_date']= \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');
        }

        //if select not other than save pr_document_name into description
        if($request->pr_document_name_id!='Other'){
            $input['description']= PrDocumentName::find($input['pr_document_name_id'])->name;
        }else{
            $data->prDocumentName()->detach();
        }
          
        
        //If document attached then first delete existing file and then new file save
        if ($request->hasFile('document')){
            $prDocument = PrDocument::findOrFail($id);
            $path = public_path('storage/'.$prDocument->path.$prDocument->file_name);
            if(File::exists($path)){
                File::delete($path);
            }

            //Now save new attachment
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
        }

        // Now update remaining inputs
            PrDocument::findOrFail($id)->update($input);
            if($request->pr_document_name_id!='Other'){
                $prDocumentNameId = $request->input("pr_document_name_id");

                //pr_employee_id is add due to validtaion before enter into database
                $data->prDocumentName()->detach();
                $data->prDocumentName()->attach($prDocumentNameId, ['pr_detail_id'=>session('pr_detail_id')]);
            }

        //Now update in all employee documents

        

        return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Updated"]);

    }



    public function destroy($id){

        $prDocument = PrDocument::findOrFail($id);

        $path = public_path('storage/'.$prDocument->path.$prDocument->file_name);
        if(File::exists($path)){
            File::delete($path);
        }
            $prDocument->forceDelete();
            HrDocumentation::where('pr_document_id', $prDocument->id)->delete();
            return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Deleted"]);

    }



    public function refreshTable(){
        $documentIds = PrDocument::where('pr_detail_id', session('pr_detail_id'))->get();
        return view('project.document.list',compact('documentIds'));
    }



}
