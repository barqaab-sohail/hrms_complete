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

use App\Models\Hr\EmployeeDesignation;
use App\Models\Hr\EmployeeDepartment;
use App\Models\Hr\EmployeeSalary;
use App\Models\Hr\EmployeeManager;
use App\Models\Hr\EmployeeProject;
use App\Models\Hr\EmployeeOffice;

use App\Models\Hr\PostingManager;
use App\Models\Hr\PostingSalary;
use App\Models\Hr\PostingDepartment;
use App\Models\Hr\PostingDesignation;
use App\Models\Hr\PostingProject;
use App\Models\Hr\PostingOffice;
use App\Http\Requests\Hr\PostingStore;
use DB;

class PostingController extends Controller
{
    
    public function create (Request $request){

    	$designations = HrDesignation::all();
    	$departments = HrDepartment::all();
    	$projects = PrDetail::all();
    	$managers = HrEmployee::with('employeeDesignation')->get();
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
                $input['document_date'] = $input ['effective_date'];

            $hrDocumentation = HrDocumentation::create($input);  
            $input['hr_documentation_id'] = $hrDocumentation->id;

            $hrPosting = HrPosting::create($input);
            $input['hr_posting_id']=$hrPosting->id;
            
            if($request->filled('hr_designation_id')){
                $employeeDesignation = EmployeeDesignation::create($input);
                $input['employee_designation_id']= $employeeDesignation->id;
                PostingDesignation::create($input);
            }

            if($request->filled('hr_department_id')){
                $employeeDepartment = EmployeeDepartment::create($input);
                $input['employee_department_id']= $employeeDepartment->id;
               PostingDepartment::create($input);
            }

            if($request->filled('hr_salary_id')){
                $employeeSalary = EmployeeSalary::create($input);
                $input['employee_salary_id']= $employeeSalary->id;
                PostingSalary::create($input);
            }

            if($request->filled('hr_manager_id')){
                $employeeManager = EmployeeManager::create($input);
                $input['employee_manager_id']= $employeeManager->id;
                PostingManager::create($input);
            }

            if($request->filled('pr_detail_id')){
                $employeeProject = EmployeeProject::create($input);
                $input['employee_project_id']= $employeeProject->id;
                Postingproject::create($input);
            }

            if($request->filled('office_id')){
                $employeeOffice = EmployeeOffice::create($input);
                $input['employee_office_id']= $employeeOffice->id;
                PostingOffice::create($input);
            }

    	});  //end transaction

    	return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);

    }

    public function edit (Request $request, $id){
        //For security checking
        session()->put('posting_edit_id', $id);

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
    	//ensure client end id is not changed
        if($id != session('posting_edit_id')){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }

            $input = $request->all();

    		if($request->filled('effective_date')){
            $input ['effective_date']= \Carbon\Carbon::parse($request->effective_date)->format('Y-m-d');
            }
    		
    	DB::transaction(function () use ($id,$input,$request) { 

           HrPosting::findOrFail($id)->update($input);
            $input['hr_employee_id']= session('hr_employee_id');
            $input['hr_posting_id']= $id;

            if($request->filled('hr_designation_id')){
                $postingDesignation = PostingDesignation::where('hr_posting_id',$id)->first();
                $employeeDesignation = EmployeeDesignation::updateOrCreate(
                        ['id'=> $postingDesignation->employee_designation_id??''],       //It is find and update 
                        $input);
                $input['employee_designation_id']= $employeeDesignation->id;
                PostingDesignation::updateOrCreate(
                        ['hr_posting_id'=> $postingDesignation->hr_posting_id??''],       //It is find and update 
                        $input);

            }else{
                $postingDesignation = PostingDesignation::where('hr_posting_id',$id)->first();
                if($postingDesignation){
                    $employeeDesignation = EmployeeDesignation::where('id',$postingDesignation->employee_designation_id)->first();
                    $postingDesignation->delete();
                    $employeeDesignation->delete();
                }
            }

            if($request->filled('hr_department_id')){
                $postingDepartment = PostingDepartment::where('hr_posting_id',$id)->first();
                $employeeDepartment = EmployeeDepartment::updateOrCreate(
                        ['id'=> $postingDepartment->employee_department_id??''],       //It is find and update 
                        $input);
                $input['employee_department_id']= $employeeDepartment->id;
                PostingDepartment::updateOrCreate(
                        ['hr_posting_id'=> $postingDepartment->hr_posting_id??''],       //It is find and update 
                        $input);

            }else{
                $postingDepartment = PostingDepartment::where('hr_posting_id',$id)->first();
                if($postingDepartment){
                    $employeeDepartment = EmployeeDepartment::where('id',$postingDepartment->employee_department_id)->first();
                    $postingDepartment->delete();
                    $employeeDepartment->delete();
                }
            }

            if($request->filled('hr_manager_id')){
                $postingManager = PostingManager::where('hr_posting_id',$id)->first();
                $employeeManager = EmployeeManager::updateOrCreate(
                        ['id'=> $postingManager->employee_manager_id??''],       //It is find and update 
                        $input);
                $input['employee_manager_id']= $employeeManager->id;
                PostingManager::updateOrCreate(
                        ['hr_posting_id'=> $postingManager->hr_posting_id??''],       //It is find and update 
                        $input);

            }else{
                $postingManager = PostingManager::where('hr_posting_id',$id)->first();
                if($postingManager){
                    $employeeManager = EmployeeManager::where('id',$postingManager->employee_manager_id)->first();
                    $postingManager->delete();
                    $employeeManager->delete();
                }
            }

            if($request->filled('hr_salary_id')){
                $postingSalary = PostingSalary::where('hr_posting_id',$id)->first();
                $employeeSalary = EmployeeSalary::updateOrCreate(
                        ['id'=> $postingSalary->employee_salary_id??''],       //It is find and update 
                        $input);
                $input['employee_salary_id']= $employeeSalary->id;

                PostingSalary::updateOrCreate(
                        ['hr_posting_id'=> $postingSalary->hr_posting_id??''],       //It is find and update 
                        $input);

            }else{
                $postingSalary = PostingSalary::where('hr_posting_id',$id)->first();
                if($postingSalary){
                    $employeeSalary = EmployeeSalary::where('id',$postingSalary->employee_salary_id)->first();
                    $postingSalary->delete();
                    $employeeSalary->delete();
                }
            }

            if($request->filled('pr_detail_id')){
                $postingProject = PostingProject::where('hr_posting_id',$id)->first();
                $employeeProject = EmployeeProject::updateOrCreate(
                        ['id'=> $postingProject->employee_project_id??''],       //It is find and update 
                        $input);
                $input['employee_project_id']= $employeeProject->id;
                PostingProject::updateOrCreate(
                        ['hr_posting_id'=> $postingProject->hr_posting_id??''],       //It is find and update 
                        $input);

            }else{
                $postingProject = PostingProject::where('hr_posting_id',$id)->first();
                if($postingProject){
                    $employeeProject = EmployeeProject::where('id',$postingProject->employee_project_id)->first();
                    $postingProject->delete();
                    $employeeProject->delete();
                }
            }

            if($request->filled('office_id')){
                $postingOffice = PostingOffice::where('hr_posting_id',$id)->first();
                $employeeOffice = EmployeeOffice::updateOrCreate(
                        ['id'=> $postingOffice->employee_office_id??''],       //It is find and update 
                        $input);
                $input['employee_office_id']= $employeeOffice->id;
                PostingOffice::updateOrCreate(
                        ['hr_posting_id'=> $postingOffice->hr_posting_id??''],       //It is find and update 
                        $input);

            }else{
                $postingOffice = PostingOffice::where('hr_posting_id',$id)->first();
                if($postingOffice){
                    $employeeOffice = EmployeeOffice::where('id',$postingOffice->employee_office_id)->first();
                    $postingOffice->delete();
                    $employeeOffice->delete();
                }
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
                $input['document_date'] = $input ['effective_date'];
                

                $hrPosting = HrPosting::find($id);

                //Delete Old File
                $hrDocument = HrDocumentation::findOrFail($hrPosting->hr_documentation_id);
                $path = public_path('storage/'.$hrDocument->path.$hrDocument->file_name);
                if(File::exists($path)){
                    File::delete($path);
                }
                //Update file detail
                HrDocumentation::findOrFail($hrDocument->id)->update($input);
            }else
             //only document date change
            {
                $input['description']=$input['remarks'];
                $input['document_date'] = $input ['effective_date'];
                $hrPosting = HrPosting::find($id);
                $hrDocument = HrDocumentation::findOrFail($hrPosting->hr_documentation_id);
                HrDocumentation::findOrFail($hrDocument->id)->update($input);
            }     
    	});  //end transaction

    	return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);

    }

    public function destroy ($id){
        if(!in_array($id, session('posting_delete_ids'))){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }

    	DB::transaction(function () use ($id) {  
            $postingSalary = PostingSalary::where('hr_posting_id',$id)->first();
            $employeeSalary = EmployeeSalary::where('id',$postingSalary->employee_salary_id??'')->first();

            $postingDesignation = PostingDesignation::where('hr_posting_id',$id)->first();
            $employeeDesignation = EmployeeDesignation::where('id',$postingDesignation->employee_designation_id??'')->first();

            $postingDepartment = PostingDepartment::where('hr_posting_id',$id)->first();
            $employeeDepartment = EmployeeDepartment::where('id',$postingDepartment->employee_department_id??'')->first();

            $postingManager = PostingManager::where('hr_posting_id',$id)->first();
            $employeeManager = EmployeeManager::where('id',$postingManager->employee_manager_id??'')->first();

            $postingOffice = PostingOffice::where('hr_posting_id',$id)->first();
            $employeeOffice = EmployeeOffice::where('id',$postingOffice->employee_office_id??'')->first();

            $postingProject = PostingProject::where('hr_posting_id',$id)->first();
            $employeeProject = EmployeeProject::where('id',$postingProject->employee_project_id??'')->first();


    	 	$hrPosting = HrPosting::find($id);
            $hrPosting->delete();
                if($employeeSalary){
                $employeeSalary->delete();
                }
                if($employeeDesignation){
                $employeeDesignation->delete();
                }
                if( $employeeDepartment){
                $employeeDepartment->delete();
                }
                if($employeeProject){
                $employeeProject->delete();
                }
                if($employeeOffice){
                $employeeOffice->delete();
                }
                if($employeeManager){
                $employeeManager->delete();
                }

            $hrDocument = HrDocumentation::findOrFail($hrPosting->hr_documentation_id);
            $path = public_path('storage/'.$hrDocument->path.$hrDocument->file_name);
            if(File::exists($path)){
                File::delete($path);
            }
            $hrDocument->forceDelete();

            //app('App\Http\Controllers\Hr\DocumentationController')->destroy($hrPosting->hr_documentation_id);
    		
    	 }); // end transcation


        return response()->json(['status'=> 'OK', 'message' => 'Data Successfully Deleted']);

    }

     public function refreshTable(){

    	$hrPostings = HrPosting::where('hr_employee_id',session('hr_employee_id'))->get();
        
        $ids = $hrPostings->pluck('id')->toArray();
        //For security checking
        session()->put('posting_delete_ids', $ids);

        return view('hr.posting.list',compact('hrPostings'));
        
    }
}
