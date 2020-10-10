<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Hr\HrDesignation;
use App\Models\Hr\HrDepartment;
use App\Models\Project\PrDetail;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrSalary;
use App\Models\Office\Office;
use App\Models\Hr\HrPosting;
use App\Models\Hr\HrDocumentation;
use App\Models\Hr\HrPostingSalary;
use App\Models\Hr\HrPostingDesignation;
use App\Models\Hr\HrPostingDepartment;
use App\Models\Hr\HrPostingManager;
use App\Models\Hr\HrPostingProject;
use App\Models\Hr\HrPostingOffice;
use App\Http\Requests\Hr\PostingStore;
use DB;

class PostingController extends Controller
{
    
    public function create (Request $request){

    	$designations = HrDesignation::all();
    	$departments = HrDepartment::all();
    	$projects = PrDetail::all();
    	$managers = HrEmployee::all();
    	$salaries = HrSalary::all();
    	$offices = Office::all();
    	$hrPostings = HrPosting::where('hr_employee_id',session('hr_employee_id'))->get();
       

		   if($request->ajax()){
	             $view =  view('hr.posting.create', compact('designations','departments','projects','managers','salaries','offices','hrPostings'))->render();

            	return response()->json($view);

	        }else{
	            return back()->withError('Please contact to administrator, SSE_JS');
	        }
    }

    public function store (PostingStore $request){
    		$input = $request->all();

    		if($request->filled('effective_date')){
            $input ['effective_date']= \Carbon\Carbon::parse($request->effective_date)->format('Y-m-d');
            }

            $employee = HrEmployee::find(session('hr_employee_id'));
            $employeeFullName = strtolower($employee->first_name) .'_'.strtolower($employee->last_name);
    		
    	DB::transaction(function () use ($request,$input, $employeeFullName) { 

            $input['hr_employee_id'] = session('hr_employee_id');

            $extension = request()->document->getClientOriginalExtension();
                $fileName =session('hr_employee_id').'-'.strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s','', $input['remarks'])).'-'. time().'.'.$extension;
                $folderName = "hr/documentation/".session('hr_employee_id').'-'.$employeeFullName."/";
                //store file
                $request->file('document')->storeAs('public/'.$folderName,$fileName);
                
                $file_path = storage_path('app/public/'.$folderName.$fileName);
            
                $input['content']='';
                                            
                    if ($extension =='pdf'){
                        $reader = new \Asika\Pdf2text;
                        $input['content'] = mb_strtolower($reader->decode($file_path));
                    }
                $input['description']=$input['remarks'];
                $input['file_name']=$fileName;
                $input['size']=$request->file('document')->getSize();
                $input['path']=$folderName;
                $input['extension']=$extension;

            $hrDocumentation = HrDocumentation::create($input);  
            $input['hr_documentation_id'] = $hrDocumentation->id;

            $hrPosting = HrPosting::create($input);
            $input['hr_posting_id']=$hrPosting->id;
            
            if($request->filled('hr_designation_id')){
                HrPostingDesignation::create($input);
            }

            if($request->filled('hr_department_id')){
                HrPostingDepartment::create($input);
            }

            if($request->filled('hr_salary_id')){
                HrPostingSalary::create($input);
            }

            if($request->filled('hr_manager_id')){
                HrPostingManager::create($input);
            }

            if($request->filled('pr_detail_id')){
                HrPostingproject::create($input);
            }

            if($request->filled('office_id')){
                HrPostingOffice::create($input);
            }



    	});  //end transaction

    	return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);

    }

    public function edit (Request $request, $id){

    	$designations = HrDesignation::all();
    	$departments = HrDepartment::all();
    	$projects = PrDetail::all();
    	$managers = HrEmployee::all();
    	$salaries = HrSalary::all();
    	$offices = Office::all();
    	$data = HrPosting::find($id);

    	$hrPostings = HrPosting::where('hr_employee_id', session('hr_employee_id'));

		   if($request->ajax()){
	             $view =  view('hr.posting.edit', compact('designations','departments','projects','managers','salaries','offices','hrPostings','data'))->render();

            	return response()->json($view);

	        }else{
	            return back()->withError('Please contact to administrator, SSE_JS');
	        }
    }

    public function update (Request $request, $id){
    		$input = $request->all();

    		if($request->filled('effective_date')){
            $input ['effective_date']= \Carbon\Carbon::parse($request->effective_date)->format('Y-m-d');
            }
    		
    	DB::transaction(function () use ($id,$input,$request) { 

           HrPosting::findOrFail($id)->update($input);

           if($request->filled('pr_detail_id')){
                HrPostingproject::updateOrCreate(
                        ['hr_posting_id'=> $id],       //It is find and update 
                        $input); 
            }else{
                HrPostingproject::where('hr_posting_id',$id)->delete();
            }

            if($request->filled('office_id')){
                HrPostingOffice::updateOrCreate(
                        ['hr_posting_id'=> $id],       //It is find and update 
                        $input); 
            }else{
                HrPostingOffice::where('hr_posting_id',$id)->delete();
            }

            if($request->filled('hr_department_id')){
                HrPostingDepartment::updateOrCreate(
                        ['hr_posting_id'=> $id],       //It is find and update 
                        $input); 
            }else{
                HrPostingDepartment::where('hr_posting_id',$id)->delete();
            }

            if($request->filled('hr_designation_id')){
                HrPostingDesignation::updateOrCreate(
                        ['hr_posting_id'=> $id],       //It is find and update 
                        $input); 
            }else{
                HrPostingDesignation::where('hr_posting_id',$id)->delete();
            }

            if($request->filled('hr_manager_id')){
                HrPostingManager::updateOrCreate(
                        ['hr_posting_id'=> $id],       //It is find and update 
                        $input); 
            }else{
                HrPostingManager::where('hr_posting_id',$id)->delete();
            }

             if($request->filled('hr_salary_id')){
                HrPostingSalary::updateOrCreate(
                        ['hr_posting_id'=> $id],       //It is find and update 
                        $input); 
            }else{
                HrPostingSalary::where('hr_posting_id',$id)->delete();
            }

            //if document 
            if($request->hasFile('document')){
                $employee = HrEmployee::find(session('hr_employee_id'));
                $employeeFullName = strtolower($employee->first_name) .'_'.strtolower($employee->last_name);

                $input['hr_employee_id'] = session('hr_employee_id');
                $extension = request()->document->getClientOriginalExtension();
                $fileName =session('hr_employee_id').'-'.strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s','', $input['remarks'])).'-'. time().'.'.$extension;
                $folderName = "hr/documentation/".session('hr_employee_id').'-'.$employeeFullName."/";
                //store file
                $request->file('document')->storeAs('public/'.$folderName,$fileName);
                
                $file_path = storage_path('app/public/'.$folderName.$fileName);
            
                $input['content']='';
                                            
                    if ($extension =='pdf'){
                        $reader = new \Asika\Pdf2text;
                        $input['content'] = mb_strtolower($reader->decode($file_path));
                    }
                $input['description']=$input['remarks'];
                $input['file_name']=$fileName;
                $input['size']=$request->file('document')->getSize();
                $input['path']=$folderName;
                $input['extension']=$extension;

                

                $hrPosting = HrPosting::find($id);

                //Delete Old File
                $hrDocument = HrDocumentation::findOrFail($hrPosting->hr_documentation_id);
                $path = public_path('storage/'.$hrDocument->path.$hrDocument->file_name);
                if(File::exists($path)){
                    File::delete($path);
                }

                //Update file detail
                HrDocumentation::findOrFail($hrDocument->id)->update($input);


            }

           
    	});  //end transaction

    	return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);

    }



    public function destroy ($id){

    	DB::transaction(function () use ($id) {  

    	 	$hrPosting = HrPosting::find($id);
            $hrPosting->delete();
            app('App\Http\Controllers\Hr\DocumentationController')->destroy($hrPosting->hr_documentation_id);
    		
    	 }); // end transcation


        return response()->json(['status'=> 'OK', 'message' => 'Data Sucessfully Deleted']);

    }

     public function refreshTable(){

    	$hrPostings = HrPosting::where('hr_employee_id',session('hr_employee_id'))->get();
       
        return view('hr.posting.list',compact('hrPostings'));
        
    }
}
