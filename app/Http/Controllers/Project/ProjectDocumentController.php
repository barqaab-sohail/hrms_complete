<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Project\DocumentStore;
use App\Models\Project\PrFolderName;
use App\Models\Project\PrDocument;
use App\Models\Project\PrDocumentContent;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDocumentation;
use App\Models\Hr\HrDocumentationProject;
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
 
    	DB::transaction(function () use ($request, $input) { 

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
            if (strlen($input['content'])>50){
                
                PrDocumentContent::create($input); 
            }

            //add document path into employee record    
            if($request->filled("hr_employee_id.0")){
                for ($i=0;$i<count($request->input('hr_employee_id'));$i++){
                $input['hr_employee_id']=$request->input("hr_employee_id.$i");
                $input['pr_document_id']=$prDocument->id;
                $hrDocumentation = HrDocumentation::create($input); 
                $input['hr_documentation_id'] = $hrDocumentation->id;
                HrDocumentationProject::create($input);

                }
            }

    	});  //end transaction

    	return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);

    }


    public function edit(Request $request, $id){


        $employees = HrEmployee::where('hr_status_id',1)->get();
        $hrDocumentationIds = HrDocumentationProject::where('pr_document_id',$id)->get()->pluck('hr_documentation_id')->toArray();

         $employeeDocuments = HrDocumentation::wherein('id',$hrDocumentationIds)->get()->pluck('hr_employee_id')->toArray();
                        
    
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
        $employeeIds = $input['hr_employee_id'];

        if($request->filled('document_date')){
            $input ['document_date']= \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');
        }
        

                           
        $prDocument = PrDocument::findOrFail($id);

        DB::transaction(function () use ($request, $input, $id, $prDocument, $employeeIds, &$data1) { 
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

                
                //update project document content  
                PrDocumentContent::updateOrCreate(
                    ['pr_document_id'=> $id],       //It is find and update 
                    $input);  
                                   

            }

            // Now update remaining inputs in pr_document
                $prDocument->update($input);


                
            
            //if any employee removing from input than delete also in Hr Documentation
            $hrDocumentationIds = HrDocumentationProject::where('pr_document_id',$id)->get()->pluck('hr_documentation_id')->toArray();

            $existingHrEmployeeIds = HrDocumentation::wherein('id',$hrDocumentationIds)->get()->pluck('hr_employee_id')->toArray();
            $existingHrEmployeeIds = collect($existingHrEmployeeIds);


            if($request->filled("hr_employee_id.0")){
                
                foreach($employeeIds as $employeeId){
                    $key = $existingHrEmployeeIds->search($employeeId);
                    $input['hr_employee_id']=$employeeId;

                    if($existingHrEmployeeIds->contains($employeeId)){   
                        HrDocumentation::wherein('id',$hrDocumentationIds)->where('hr_employee_id',$employeeId)->first()->update($input);
                        $existingHrEmployeeIds->pull($key);
                    }else{

                        $input['file_name']= $prDocument->file_name;
                        $input['size']=$prDocument->size;
                        $input['path']=$prDocument->path;
                        $input['extension']=$prDocument->extension;
                        $input['pr_document_id']=$id;
                        $hrDocumentation = HrDocumentation::create($input); 
                        $input['hr_documentation_id'] = $hrDocumentation->id;
                        HrDocumentationProject::create($input);
                       
                    }
                }

                //remaining delete here
                    $data1 = HrDocumentation::wherein('id',$hrDocumentationIds)->wherein('hr_employee_id', $existingHrEmployeeIds)->delete();
            }

                $data1 = json_encode($existingHrEmployeeIds);
                 
               
                // //Now update in all employee documents
                // if($request->filled("hr_employee_id.0")){
                                
                //     //for update or create Hr Documentation
                //     for ($i=0;$i<count($request->input('hr_employee_id'));$i++){
                //         //array of hr_employee_id into single value;
                //         $input['hr_employee_id']=$request->input("hr_employee_id.$i");
                //         //This information requried if new employee add in request
                //         $input['file_name']= $prDocument->file_name;
                //         $input['size']=$prDocument->size;
                //         $input['path']=$prDocument->path;
                //         $input['extension']=$prDocument->extension;

                //         //After update (may be delete) get current status of Hr Documentation Project
                //         $currentHrDocumentationProjectIds = HrDocumentationProject::where('pr_document_id',$id)->get()->pluck('hr_documentation_id')->toArray();

                //         foreach($currentHrDocumentationProjectIds as $currentId){

                //             $curentHrDocumentation = HrDocumentation::find($currentId);
                //             $curentHrDocumentation->update($input);

                //         }

                //         HrDocumentation::updateOrCreate(
                //         ['hr_employee_id' => $input['hr_employee_id'],'pr_document_id'=> $prDocument->id],   //It is find and update 
                //         $input);                                //If first one not found than create
                //     }

                // }else{
                //     //If no fill hr_employee_id then delete from employee documentation
                //     HrDocumentation::where('pr_document_id', $prDocument->id)->delete();
                // }

            });  //end transaction

        return response()->json(['status'=> 'OK', 'message' => "$data1 This Module is under progress"]);

    }


    public function destroy($id){

        DB::transaction(function () use ($id) { 

            $prDocument = PrDocument::findOrFail($id);

            $path = public_path('storage/'.$prDocument->path.$prDocument->file_name);
         
                $hrDocumentationIds = HrDocumentationProject::where('pr_document_id',$id)->get()->pluck('hr_documentation_id')->toArray();
                HrDocumentation::wherein('id', $hrDocumentationIds)->delete();
                $prDocument->forceDelete();

                if(File::exists($path)){
                    File::delete($path);
                }

        });  //end transaction
            

            return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Deleted"]);

    }



    public function refreshTable(){
        $documentIds = PrDocument::where('pr_detail_id', session('pr_detail_id'))->get();
        return view('project.document.list',compact('documentIds'));
    }



}
