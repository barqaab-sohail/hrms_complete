<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Hr\HrDocumentName;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDocumentation;
use App\Models\Hr\HrDocumentNameDocumentation;
use App\Http\Requests\Hr\DocumentationStore;
use DB;

class DocumentationController extends Controller
{
    public function create(Request $request){

    	$documentNames = HrDocumentName::all();

        if($request->ajax()){
            $view = view ('hr.documentation.create',compact('documentNames'))->render();
            return response()->json($view);
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }
    }

    public function store(DocumentationStore $request){

    	$input = $request->only('hr_document_name_id','description','document');

            if($request->filled('document_date')){
            $input ['document_date']= \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');
            }

    		if ($request->filled('description')) {
    		//
			}else{
				$input['description']= HrDocumentName::find($input['hr_document_name_id'])->name;
			}

			$employee = HrEmployee::find(session('hr_employee_id'));
			$employeeFullName = strtolower($employee->first_name) .'_'.strtolower($employee->last_name);



    	DB::transaction(function () use ($request, $input, $employeeFullName) { 

    			$extension = request()->document->getClientOriginalExtension();
				$fileName =session('hr_employee_id').'-'.$input['description'].'-'. time().'.'.$extension;
				$folderName = "hr/documentation/".session('hr_employee_id').'-'.$employeeFullName."/";
				//store file
				$request->file('document')->storeAs('public/'.$folderName,$fileName);
				
				$file_path = storage_path('app/public/'.$folderName.$fileName);
			
				$input['content']='';
											
					if ($extension =='pdf'){
						$reader = new \Asika\Pdf2text;
						$input['content'] = mb_strtolower($reader->decode($file_path));
					}

				$input['file_name']=$fileName;
				$input['size']=$request->file('document')->getSize();
				$input['path']=$folderName;
				$input['extension']=$extension;
				$input['hr_employee_id']=session('hr_employee_id');

			
            $hrDocumentation = HrDocumentation::create($input);  

            if($request->hr_document_name_id!='Other'){
			$hrDocumentNameId = $request->input("hr_document_name_id");

            //hr_employee_id is add due to validtaion before enter into database
			$hrDocumentation->hrDocumentName()->attach($hrDocumentNameId, ['hr_employee_id'=>session('hr_employee_id')]);	
            }	


    	});  //end transaction

    	return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);

    }

    public function edit(Request $request, $id){

    	$documentNames = HrDocumentName::all();
    	$data = HrDocumentation::find($id);
        $documentNameExist = HrDocumentNameDocumentation::where('hr_documentation_id',$data->id)->first();

        if($request->ajax()){
            return view ('hr.documentation.edit',compact('documentNames','data','documentNameExist'));
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }


    }



    public function update(Request $request, $id){

        $input = $request->only('hr_document_name_id','description','document','document_date');
        $data = HrDocumentation::find($id);

        if($request->filled('document_date')){
            $input ['document_date']= \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');
        }

        if($request->hr_document_name_id!='Other'){
            $input['description']= HrDocumentName::find($input['hr_document_name_id'])->name;
        }
          
        
          
        if ($request->hasFile('document')){


        }else{

               HrDocumentation::findOrFail($id)->update($input);
                if($request->hr_document_name_id!='Other'){
                    $hrDocumentNameId = $request->input("hr_document_name_id");

                    //hr_employee_id is add due to validtaion before enter into database
                    $data->hrDocumentName()->detach();
                    $data->hrDocumentName()->attach($hrDocumentNameId, ['hr_employee_id'=>session('hr_employee_id')]);

                }

        }

        // $data = HrDocumentation::find($id);
        // $input = $request->only('hr_document_name_id','description','document');

        //     if ($request->filled('description')) {
                    
        //     }else{
        //         $input['description']= HrDocumentName::find($input['hr_document_name_id'])->name;
        //     }

        //     $employee = HrEmployee::find(session('hr_employee_id'));
        //     $employeeFullName = strtolower($employee->first_name) .'_'.strtolower($employee->last_name);



        // DB::transaction(function () use ($request, $input, $employeeFullName, $data, $id, &$hrDocumentNameId) { 

                
        //     if ($request->hasFile('document')){
        //         $extension = request()->document->getClientOriginalExtension();
        //         $fileName =session('hr_employee_id').'-'.$input['description'].'-'. time().'.'.$extension;
        //         $folderName = $data->path;
        //         //store file
        //         $request->file('document')->storeAs('public/'.$folderName,$fileName);
                
        //         $file_path = storage_path('app/public/'.$folderName.$fileName);
            
        //         $input['content']='';
                                            
        //             if ($extension =='pdf'){
        //                 $reader = new \Asika\Pdf2text;
        //                 $input['content'] = mb_strtolower($reader->decode($file_path));
        //             }

        //         $input['file_name']=$fileName;
        //         $input['size']=$request->file('document')->getSize();
        //         //$input['path']=$folderName;
        //         $input['extension']=$extension;
        //         $input['hr_employee_id']=session('hr_employee_id');

        //         HrDocumentation::findOrFail($id)->update($input);
        //     }else{

        //         HrDocumentation::findOrFail($id)->update(['description'=>$input['description']]);
        //         if($request->hr_document_name_id!='Other'){
        //         $hrDocumentNameId = $request->input("hr_document_name_id");

        //         //hr_employee_id is add due to validtaion before enter into database
        //         $data->hrDocumentName()->updateExistingPivot($hrDocumentNameId, ['hr_employee_id'=>session('hr_employee_id')]);   
        //         }
        //     }


        // });  //end transaction

        return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Updated"]);



    }



    public function destroy($id){

    	$hrDocument = HrDocumentation::findOrFail($id);

        $path = public_path('storage/'.$hrDocument->path.$hrDocument->file_name);
        if(File::exists($path)){
            File::delete($path);
        }
            $hrDocument->forceDelete();
            return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Deleted"]);

    }

    public function refreshTable(){
        $documentIds = HrDocumentation::where('hr_employee_id', session('hr_employee_id'))->get();
        return view('hr.documentation.list',compact('documentIds'));
    }
}