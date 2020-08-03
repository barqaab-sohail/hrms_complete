<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Project\DocumentStore;
use App\Models\Project\PrFolderName;
use App\Models\Project\PrDocument;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDocumentation;
use App\Helper\DocxConversion;
use DB;

class ProjectDocumentController extends Controller
{   
    

    public function show ($id){
        $folderName = PrFolderName::find($id);
        $documentIds = PrDocument::where('pr_detail_id', session('pr_detail_id'))
        ->where('pr_folder_name_id',$id)->get();
        return view('project.document.list',compact('documentIds','folderName'));
    }

    public function create(Request $request){

    	$prFolderNames = PrFolderName::all();
        $documentIds = PrDocument::where('pr_detail_id', session('pr_detail_id'))->get();

        $employees = HrEmployee::where('hr_status_id',1)->get();

        if($request->ajax()){
            $view = view ('project.document.create',compact('prFolderNames','documentIds','employees'))->render();
            return response()->json($view);
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }


    }


    public function store(DocumentStore $request){

        $input = $request->all();

            
            if($request->filled('document_date')){
            $input ['document_date']= \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');
            }
 
    	DB::transaction(function () use ($request, $input, &$test) { 

    			$extension = request()->document->getClientOriginalExtension();
                
                $fileName =strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s','', $input['description'])).'-'. time().'.'.$extension;
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
            $input['pr_document_id'] = $prDocument->id;
            // $test = $input['content'];
            //$test = strlen($input['content']);
            if (strlen($input['content'])>100){
                $test = $input['content'];
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

    	return response()->json(['status'=> 'OK', 'message' => "$test Data Sucessfully Saved"]);

    }


    public function edit(Request $request, $id){


        $employees = HrEmployee::where('hr_status_id',1)->get();
        
        $employeeDocuments = HrDocumentation::where('pr_document_id',$id)->get()->pluck('hr_employee_id')->toArray();
        $prFolderNames = PrFolderName::all();

        $data = PrDocument::find($id);
       
        if($request->ajax()){
            return view ('project.document.edit',compact('prFolderNames','data','employees','employeeDocuments'));
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

               
        $prDocument = PrDocument::findOrFail($id);

          
        //If document attached then first delete existing file and then new file save
        if ($request->hasFile('document')){
            
            //Delete Existing Document
            $path = public_path('storage/'.$prDocument->path.$prDocument->file_name);
            if(File::exists($path)){
                File::delete($path);
            }

            //Now save new Document
            $extension = request()->document->getClientOriginalExtension();
            $fileName =strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s','', $input['description'])).'-'. time().'.'.$extension;
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

        // Now update remaining inputs in pr_document
            $prDocument->update($input);
            
        
        //if any employee removing from input than delete also in Hr Documentation
            $existingHrDocument = HrDocumentation::where('pr_document_id', $prDocument->id)->get()->pluck('hr_employee_id')->toArray();
            //$data1 = json_encode($existingHrDocument);

            if($request->filled("hr_employee_id.0")){
                //create collection
                 $existingHrDocument = collect($existingHrDocument);
                 
                //find required employee and exclude.  Remaining all are data delete after forloop
                for ($i=0;$i<count($input['hr_employee_id']);$i++){
                    
                    $key = $existingHrDocument->search($request->input("hr_employee_id.$i"));
                    if($key){
                        $existingHrDocument->pull($key);
                    }
                }
                    //Now remaining delete from HR Documentation
                     //$data2 = json_encode($existingHrDocument);


                   HrDocumentation::where('pr_document_id',$id)->wherein('hr_employee_id',$existingHrDocument)->delete();
                    

            }

            //Now update in all employee documents
            if($request->filled("hr_employee_id.0")){
                            
                //for update or create Hr Documentation
                for ($i=0;$i<count($request->input('hr_employee_id'));$i++){
                    //array of hr_employee_id into single value;
                    $input['hr_employee_id']=$request->input("hr_employee_id.$i");
                    //This information requried if new employee add in request
                    $input['file_name']= $prDocument->file_name;
                    $input['size']=$prDocument->size;
                    $input['path']=$prDocument->path;
                    $input['extension']=$prDocument->extension;

                    HrDocumentation::updateOrCreate(
                    ['hr_employee_id' => $input['hr_employee_id'],'pr_document_id'=> $prDocument->id],   //It is find and update 
                    $input);                                //If first one not found than create
                }

            }else{
                //If no fill hr_employee_id then delete from employee documentation
                HrDocumentation::where('pr_document_id', $prDocument->id)->delete();
            }

        return response()->json(['status'=> 'OK', 'message' => "Data Successfully Updated"]);

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
