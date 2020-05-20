<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrPromotion;
use App\Models\Hr\HrSalary;
use App\Models\Hr\HrDesignation;
use App\Models\Hr\HrDocumentation;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDepartment;
use App\Http\Requests\Hr\PromotionStore;
use DB;

class PromotionController extends Controller
{
    
	public function create (Request $request){

		$salaries = HrSalary::all();
		$designations = HrDesignation::all();
		$managers = HrEmployee::all();
		$departments = HrDepartment::all();
		

	    	$hrPromotions =  HrPromotion::where('hr_employee_id', session('hr_employee_id'))->get();

	        if($request->ajax()){
	             $view =  view('hr.promotion.create', compact('salaries','designations','managers','departments','hrPromotions'))->render();

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
				$fileName =session('hr_employee_id').'-'.$input['remarks'].'-'. time().'.'.$extension;
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

            HrPromotion::create($input);


    	});  //end transaction

    	return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);

    }

    public function edit(Request $request, $id){
    	$salaries = HrSalary::all();
		$designations = HrDesignation::all();
		$managers = HrEmployee::all();
		$departments = HrDepartment::all();
		$hrPromotions =  HrPromotion::where('hr_employee_id', session('hr_employee_id'))->get();
    	$data = HrPromotion::find($id);

    	if($request->ajax()){
	    
           $view =  view('hr.promotion.edit', compact('salaries','designations','managers','departments','hrPromotions','data'))->render();
            return response()->json($view);
		}else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }

    }


    public function update(Request $request, $id){

    	 $input = $request->all();
    		if($request->filled('effective_date')){
            $input ['effective_date']= \Carbon\Carbon::parse($request->effective_date)->format('Y-m-d');
            }
       
        DB::transaction(function () use ($input, $id) {  

            HrPromotion::findOrFail($id)->update($input);

        }); // end transcation
        
        return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Updated"]);
    }


    public function refreshTable(){

    	$hrPromotions = HrPromotion::where('hr_employee_id',session('hr_employee_id'))->get();
       
        return view('hr.promotion.list',compact('hrPromotions'));
        
    }

}
