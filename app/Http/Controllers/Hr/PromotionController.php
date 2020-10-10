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
use App\Models\Hr\HrPromotionDesignation;
use App\Models\Hr\HrPromotionDepartment;
use App\Models\Hr\HrPromotionSalary;
use App\Models\Hr\HrPromotionManager;
use App\Models\Hr\HrPromotionGrade;
use App\Models\Hr\HrPromotionCategory;
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

             
       
       
        $hrPromotions = HrPromotion::where('hr_employee_id',session('hr_employee_id'))->get();
       
		
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
				$input['extension']=$extension;
				$input['hr_employee_id']=session('hr_employee_id');

			
            $hrDocumentation = HrDocumentation::create($input);  

            $input['hr_documentation_id'] = $hrDocumentation->id;

            $hrPromotion = HrPromotion::create($input);
            $input['hr_promotion_id']=$hrPromotion->id;

            if($request->filled('hr_designation_id')){
                HrPromotionDesignation::create($input);
            }

            if($request->filled('hr_department_id')){
                HrPromotionDepartment::create($input);
            }

            if($request->filled('hr_salary_id')){
                HrPromotionSalary::create($input);
            }

            if($request->filled('hr_manager_id')){
                HrPromotionManager::create($input);
            }

            if($request->filled('hr_grade_id')){
                HrPromotionGrade::create($input);
            }

            if($request->filled('hr_category_id')){
                HrPromotionCategory::create($input);
            }


    	});  //end transaction

    	return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);

    }

    public function edit(Request $request, $id){
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

    	 $input = $request->all();
    		if($request->filled('effective_date')){
            $input ['effective_date']= \Carbon\Carbon::parse($request->effective_date)->format('Y-m-d');
            }

                               
        DB::transaction(function () use ($input, $id, $request) {  

            HrPromotion::findOrFail($id)->update($input);

            if($request->filled('hr_department_id')){
                HrPromotionDepartment::updateOrCreate(
                        ['hr_promotion_id'=> $id],       //It is find and update 
                        $input); 
            }else{
                HrPromotionDepartment::where('hr_promotion_id',$id)->delete();
            }

            if($request->filled('hr_designation_id')){
                HrPromotionDesignation::updateOrCreate(
                        ['hr_promotion_id'=> $id],       //It is find and update 
                        $input); 
            }else{
                HrPromotionDesignation::where('hr_promotion_id',$id)->delete();
            }

            if($request->filled('hr_manager_id')){
                HrPromotionManager::updateOrCreate(
                        ['hr_promotion_id'=> $id],       //It is find and update 
                        $input); 
            }else{
                HrPromotionManager::where('hr_promotion_id',$id)->delete();
            }

            if($request->filled('hr_salary_id')){
                HrPromotionSalary::updateOrCreate(
                        ['hr_promotion_id'=> $id],       //It is find and update 
                        $input); 
            }else{
                HrPromotionSalary::where('hr_promotion_id',$id)->delete();
            }

            if($request->filled('hr_grade_id')){
                HrPromotionGrade::updateOrCreate(
                        ['hr_promotion_id'=> $id],       //It is find and update 
                        $input); 
            }else{
                HrPromotionGrade::where('hr_promotion_id',$id)->delete();
            }

            if($request->filled('hr_category_id')){
                HrPromotionCategory::updateOrCreate(
                        ['hr_promotion_id'=> $id],       //It is find and update 
                        $input); 
            }else{
                HrPromotionCategory::where('hr_promotion_id',$id)->delete();
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

                $hrPromotion = HrPromotion::find($id);

                //Delete Old File
                $hrDocument = HrDocumentation::findOrFail($hrPromotion->hr_documentation_id);
                $path = public_path('storage/'.$hrDocument->path.$hrDocument->file_name);
                if(File::exists($path)){
                    File::delete($path);
                }

                //Update file detail
                HrDocumentation::findOrFail($hrDocument->id)->update($input);

        	}
            

        }); // end transcation
        
        return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Updated"]);
    }

    public function destroy ($id){

    	DB::transaction(function () use ($id) {  

            $hrPromotion = HrPromotion::find($id);
            $hrPromotion->delete();
            app('App\Http\Controllers\Hr\DocumentationController')->destroy($hrPromotion->hr_documentation_id);
            
        }); // end transcation


        return response()->json(['status'=> 'OK', 'message' => 'Data Sucessfully Deleted']);

    }


    public function refreshTable(){

    	$hrPromotions = HrPromotion::where('hr_employee_id',session('hr_employee_id'))->get();
       
        return view('hr.promotion.list',compact('hrPromotions'));
        
    }

}
