<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Hr\HrPromotion;
use App\Models\Hr\HrSalary;
use App\Models\Hr\HrDesignation;
use App\Models\Hr\HrDocumentation;
use App\Models\Hr\HrGrade;
use App\Models\Hr\HrCategory;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDepartment;
use App\Models\Hr\EmployeeDesignation;
use App\Models\Hr\EmployeeDepartment;
use App\Models\Hr\EmployeeSalary;
use App\Models\Hr\EmployeeManager;
use App\Models\Hr\EmployeeGrade;
use App\Models\Hr\EmployeeCategory;
use App\Models\Hr\PromotionCategory;
use App\Models\Hr\PromotionGrade;
use App\Models\Hr\PromotionManager;
use App\Models\Hr\PromotionSalary;
use App\Models\Hr\PromotionDepartment;
use App\Models\Hr\PromotionDesignation;
use App\Http\Requests\Hr\PromotionStore;
use DB;

class PromotionController extends Controller
{
    
	public function create (Request $request){

		$salaries = HrSalary::all();
		$designations = HrDesignation::all();
		$managers = HrEmployee::all();
		$departments = HrDepartment::all();
        $hrGrades = HrGrade::all();
        $hrCategories = HrCategory::all();
       
        $hrPromotions = HrPromotion::where('hr_employee_id',session('hr_employee_id'))->with('hrDocumentation')->orderByRaw('ISNULL(effective_date), effective_date desc')->get();
       
		
	        if($request->ajax()){
	             $view =  view('hr.promotion.create', compact('salaries','designations','managers','departments','hrGrades','hrCategories','hrPromotions'))->render();

            	return response()->json($view);

	        }else{
	            return back()->withError('Please contact to administrator, SSE_JS');
	        }
    }

    public function store (PromotionStore $request){
    		$input = $request->all();

    		if($request->filled('effective_date')){
            $input ['effective_date']= \Carbon\Carbon::parse($request->effective_date)->format('Y-m-d');
            }

    		$employee = HrEmployee::find(session('hr_employee_id'));
			$employeeFullName = strtolower($employee->first_name) .'_'.strtolower($employee->last_name);

    	DB::transaction(function () use ($request,$input, $employeeFullName) { 

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
                $input['document_date'] = $input ['effective_date'];
				$input['extension']=$extension;
				$input['hr_employee_id']=session('hr_employee_id');

			
            $hrDocumentation = HrDocumentation::create($input);  

            $input['hr_documentation_id'] = $hrDocumentation->id;

            $hrPromotion = HrPromotion::create($input);
            $input['hr_promotion_id']=$hrPromotion->id;

            if($request->filled('hr_designation_id')){
                $employeeDesignation = EmployeeDesignation::create($input);
                $input['employee_designation_id']= $employeeDesignation->id;
                PromotionDesignation::create($input);
            }

            if($request->filled('hr_department_id')){
                $employeeDepartment = EmployeeDepartment::create($input);
                $input['employee_department_id']= $employeeDepartment->id;
                PromotionDepartment::create($input);
            }

            if($request->filled('hr_salary_id')){
                $employeeSalary = EmployeeSalary::create($input);
                $input['employee_salary_id']= $employeeSalary->id;
                PromotionSalary::create($input);
            }

            if($request->filled('hr_manager_id')){
                $employeeManager = EmployeeManager::create($input);
                $input['employee_manager_id']= $employeeManager->id;
                PromotionManager::create($input);
            }

            if($request->filled('hr_grade_id')){
                $employeeGrade = EmployeeGrade::create($input);
                $input['employee_grade_id']= $employeeGrade->id;
                PromotionGrade::create($input);
            }

            if($request->filled('hr_category_id')){
                $employeeCategory = EmployeeCategory::create($input);
                $input['employee_category_id']= $employeeCategory->id;
                PromotionCategory::create($input);
            }

    	});  //end transaction

    	return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);

    }

    public function edit(Request $request, $id){
    	//For security checking
        session()->put('promotion_edit_id', $id);

        $salaries = HrSalary::all();
		$designations = HrDesignation::all();
		$managers = HrEmployee::all();
		$departments = HrDepartment::all();
        $hrGrades = HrGrade::all();
        $hrCategories = HrCategory::all();
		$hrPromotions =  HrPromotion::where('hr_employee_id', session('hr_employee_id'))->get();
    	$data = HrPromotion::find($id);


    	if($request->ajax()){
	    
           $view =  view('hr.promotion.edit', compact('salaries','designations','managers','departments','hrPromotions','hrGrades','hrCategories','data'))->render();
            return response()->json($view);
		}else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }

    }


    public function update(PromotionStore $request, $id){
        //ensure client end id is not changed
        if($id != session('promotion_edit_id')){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }

    	 $input = $request->all();
    		if($request->filled('effective_date')){
            $input ['effective_date']= \Carbon\Carbon::parse($request->effective_date)->format('Y-m-d');
            }

                               
        DB::transaction(function () use ($input, $id, $request) {  

            HrPromotion::findOrFail($id)->update($input);
             $input['hr_employee_id']= session('hr_employee_id');
             $input['hr_promotion_id']= $id;

             //check desination filled or not
            if($request->filled('hr_designation_id')){
                $promotionDesignation = PromotionDesignation::where('hr_promotion_id',$id)->first();
                $employeeDesignation = EmployeeDesignation::updateOrCreate(
                        ['id'=> $promotionDesignation->employee_designation_id??''],       //It is find and update 
                        $input);
                $input['employee_designation_id']= $employeeDesignation->id;
                PromotionDesignation::updateOrCreate(
                        ['hr_promotion_id'=> $promotionDesignation->hr_promotion_id??''],       //It is find and update 
                        $input);

            }else{
                $promotionDesignation = PromotionDesignation::where('hr_promotion_id',$id)->first();
                if($promotionDesignation){
                    $employeeDesignation = EmployeeDesignation::where('id',$promotionDesignation->employee_designation_id)->first();
                    $promotionDesignation->delete();
                    $employeeDesignation->delete();
                }
            }

            if($request->filled('hr_department_id')){
                $promotionDepartment = PromotionDepartment::where('hr_promotion_id',$id)->first();
                $employeeDepartment = EmployeeDepartment::updateOrCreate(
                        ['id'=> $promotionDepartment->employee_department_id??''],       //It is find and update 
                        $input);
                $input['employee_department_id']= $employeeDepartment->id;
                PromotionDepartment::updateOrCreate(
                        ['hr_promotion_id'=> $promotionDepartment->hr_promotion_id??''],       //It is find and update 
                        $input);

            }else{
                $promotionDepartment = PromotionDepartment::where('hr_promotion_id',$id)->first();
                if($promotionDepartment){
                    $employeeDepartment = EmployeeDepartment::where('id',$promotionDepartment->employee_department_id)->first();
                    $promotionDepartment->delete();
                    $employeeDepartment->delete();
                }
            }

            if($request->filled('hr_category_id')){
                $promotionCategory = PromotionCategory::where('hr_promotion_id',$id)->first();
                $employeeCategory = EmployeeCategory::updateOrCreate(
                        ['id'=> $promotionCategory->employee_category_id??''],       //It is find and update 
                        $input);
                $input['employee_category_id']= $employeeCategory->id;
                PromotionCategory::updateOrCreate(
                        ['hr_promotion_id'=> $promotionCategory->hr_promotion_id??''],       //It is find and update 
                        $input);
            }else{
                $promotionCategory = PromotionCategory::where('hr_promotion_id',$id)->first();
                if($promotionCategory){
                    $employeeCategory = EmployeeCategory::where('id',$promotionCategory->employee_category_id)->first();
                    $promotionCategory->delete();
                    $employeeCategory->delete();
                }
            }

            if($request->filled('hr_manager_id')){
                $promotionManager = PromotionManager::where('hr_promotion_id',$id)->first();
                $employeeManager = EmployeeManager::updateOrCreate(
                        ['id'=> $promotionManager->employee_manager_id??''],       //It is find and update 
                        $input);
                $input['employee_manager_id']= $employeeManager->id;
                PromotionManager::updateOrCreate(
                        ['hr_promotion_id'=> $promotionManager->hr_promotion_id??''],       //It is find and update 
                        $input);

            }else{
                $promotionManager = PromotionManager::where('hr_promotion_id',$id)->first();
                if($promotionManager){
                    $employeeManager = EmployeeManager::where('id',$promotionManager->employee_manager_id)->first();
                    $promotionManager->delete();
                    $employeeManager->delete();
                }
            }

            if($request->filled('hr_salary_id')){
                $promotionSalary = PromotionSalary::where('hr_promotion_id',$id)->first();
                $employeeSalary = EmployeeSalary::updateOrCreate(
                        ['id'=> $promotionSalary->employee_salary_id??''],       //It is find and update 
                        $input);
                $input['employee_salary_id']= $employeeSalary->id;
                PromotionSalary::updateOrCreate(
                        ['hr_promotion_id'=> $promotionSalary->hr_promotion_id??''],       //It is find and update 
                        $input);

            }else{
                $promotionSalary = PromotionSalary::where('hr_promotion_id',$id)->first();
                if($promotionSalary){
                    $employeeSalary = EmployeeSalary::where('id',$promotionSalary->employee_salary_id)->first();
                    $promotionSalary->delete();
                    $employeeSalary->delete();
                }
            }

            if($request->filled('hr_grade_id')){
                $promotionGrade = PromotionGrade::where('hr_promotion_id',$id)->first();
                $employeeGrade = EmployeeGrade::updateOrCreate(
                        ['id'=> $promotionGrade->employee_grade_id??''],       //It is find and update 
                        $input);
                $input['employee_grade_id']= $employeeGrade->id;
                PromotionGrade::updateOrCreate(
                        ['hr_promotion_id'=> $promotionGrade->hr_promotion_id??''],       //It is find and update 
                        $input);

            }else{
                $promotionGrade = PromotionGrade::where('hr_promotion_id',$id)->first();
                if($promotionGrade){
                    $employeeGrade = EmployeeGrade::where('id',$promotionGrade->employee_grade_id)->first();
                    $promotionGrade->delete();
                    $employeeGrade->delete();
                }
            }

        	if ($request->hasFile('document')){

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

                $hrPromotion = HrPromotion::find($id);

                //Delete Old File
                $hrDocument = HrDocumentation::findOrFail($hrPromotion->hr_documentation_id);
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
                $hrPromotion = HrPromotion::find($id);
                $hrDocument = HrDocumentation::findOrFail($hrPromotion->hr_documentation_id);
                HrDocumentation::findOrFail($hrDocument->id)->update($input);
            }
            

        }); // end transcation
        
        return response()->json(['status'=> 'OK', 'message' => "Data Successfully Updated"]);
    }

    public function destroy ($id){
        if(!in_array($id, session('promotion_delete_ids'))){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }

    	DB::transaction(function () use ($id) {  
            $promotionSalary = PromotionSalary::where('hr_promotion_id',$id)->first();
            $employeeSalary = EmployeeSalary::where('id',$promotionSalary->employee_salary_id??'')->first();

            $promotionDesignation = PromotionDesignation::where('hr_promotion_id',$id)->first();
            $employeeDesignation = EmployeeDesignation::where('id',$promotionDesignation->employee_designation_id??'')->first();

            $promotionDepartment = PromotionDepartment::where('hr_promotion_id',$id)->first();
            $employeeDepartment = EmployeeDepartment::where('id',$promotionDepartment->employee_department_id??'')->first();

            $promotionCategory = PromotionCategory::where('hr_promotion_id',$id)->first();
            $employeeCategory = EmployeeCategory::where('id',$promotionCategory->employee_category_id??'')->first();

            $promotionGrade = PromotionGrade::where('hr_promotion_id',$id)->first();
            $employeeGrade = EmployeeGrade::where('id',$promotionGrade->employee_grade_id??'')->first();

            $promotionManager = PromotionManager::where('hr_promotion_id',$id)->first();
            $employeeManager = EmployeeManager::where('id',$promotionManager->employee_manager_id??'')->first();
                    


            $hrPromotion = HrPromotion::find($id);
            $hrPromotion->delete();
                if($employeeSalary){
                $employeeSalary->delete();
                }
                if($employeeDesignation){
                $employeeDesignation->delete();
                }
                if( $employeeDepartment){
                $employeeDepartment->delete();
                }
                if($employeeCategory){
                $employeeCategory->delete();
                }
                if($employeeGrade){
                $employeeGrade->delete();
                }
                if($employeeManager){
                $employeeManager->delete();
                }

            $hrDocument = HrDocumentation::findOrFail($hrPromotion->hr_documentation_id);
            $path = public_path('storage/'.$hrDocument->path.$hrDocument->file_name);
            if(File::exists($path)){
                File::delete($path);
            }
            $hrDocument->forceDelete();
            

            //app('App\Http\Controllers\Hr\DocumentationController')->destroy($hrPromotion->hr_documentation_id);
            
        }); // end transcation


        return response()->json(['status'=> 'OK', 'message' => 'Data Successfully Deleted']);

    }


    public function refreshTable(){

    	$hrPromotions = HrPromotion::where('hr_employee_id',session('hr_employee_id'))->with('hrDocumentation')->orderByRaw('ISNULL(effective_date), effective_date desc')->get();
        $ids = $hrPromotions->pluck('id')->toArray();
        //For security checking
        session()->put('promotion_delete_ids', $ids);
        return view('hr.promotion.list',compact('hrPromotions'));
        
    }

}
