<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Gender;
use App\Models\Common\MaritalStatus;
use App\Models\Common\Religion;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\EmployeeAppointment;
use App\Models\Hr\EmployeeCategory;
use App\Models\Hr\EmployeeManager;
use App\Models\Hr\EmployeeDesignation;
use App\Models\Hr\EmployeeDepartment;
use App\Models\Hr\EmployeeGrade;
use App\Models\Hr\EmployeeOffice;
use App\Models\Hr\EmployeeSalary;
use App\Models\Hr\EmployeeProject;
use App\Models\Hr\HrContact;
use App\Models\Hr\HrStatus;
use App\Models\Hr\HrContactMobile;
use App\Models\Hr\HrEmergency;
use DB;
use App\Http\Requests\Hr\EmployeeStore;


class EmployeeController extends Controller
{
    //public function insert(){

    //         $number=1;
    //         $str_to_insert=0;
    //         $pos=4;


    // $employees = HrEmployee::where('hr_status_id',1)->get();
    // foreach($employees as $employee){
            
    //     if($employee->employee_no){
    //         $length = strlen($employee->employee_no);

    //         if ($length == 6){
    //             $newstr = substr_replace($employee->employee_no, $str_to_insert, $pos, 0);
    //             echo $employee->id.'-'.$newstr.'-'.$employee->employee_no;
    //             echo '<br>';
    //             $number = $number+1;

    //             // $employee->employee_no = $newstr;
    //             // $employee->save();
    //         }
    //     }
    // }
        
       
    // foreach ($employees as $employee){
    //     if($employee->hrAppointment){
    //         $input['hr_employee_id'] = $employee->id;  
    //         $input['pr_detail_id']= $employee->hrAppointment->pr_detail_id;
    //         $input['hr_letter_type_id']= $employee->hrAppointment->hr_letter_type_id;
    //         $input['hr_manager_id']= $employee->hrAppointment->hr_manager_id;
    //         $input['hr_designation_id']= $employee->hrAppointment->hr_designation_id;
    //         $input['hr_department_id']= $employee->hrAppointment->hr_department_id;
    //         $input['hr_salary_id']= $employee->hrAppointment->hr_salary_id;
    //         $input['office_id']= $employee->hrAppointment->office_id;
    //         $input['reference_no']= $employee->hrAppointment->reference_no;
    //         $input['joining_date']= $employee->hrAppointment->joining_date;
    //         $input['expiry_date']= $employee->hrAppointment->expiry_date;
    //         $input['hr_grade_id']= $employee->hrAppointment->hr_grade_id;
    //         $input['hr_category_id']= $employee->hrAppointment->hr_category_id;
    //         $input['hr_employee_type_id']= $employee->hrAppointment->hr_employee_type_id;
    //         $input['remakrs']= $employee->hrAppointment->remarks;
    //         $input['created_at']= $employee->hrAppointment->created_at;
    //         $input['updated_at']= $employee->hrAppointment->updated_at;
    //         EmployeeAppointment::create($input);

         
    //         $input['effective_date'] = $employee->hrAppointment->joining_date;
    //         $input['hr_designation_id'] = $employee->hrAppointment->hr_designation_id;
    //         $input['office_id'] = $employee->hrAppointment->office_id;
    //         $input['hr_category_id'] = $employee->hrAppointment->hr_category_id;
    //         $input['hr_department_id'] = $employee->hrAppointment->hr_department_id;
    //         $input['hr_salary_id'] = $employee->hrAppointment->hr_salary_id;
    //         $input['pr_detail_id'] = $employee->hrAppointment->pr_detail_id;
    //         $input['hr_grade_id'] = $employee->hrAppointment->hr_grade_id??'';

    //         EmployeeManager::create($input);
    //         EmployeeDesignation::create($input);
    //         EmployeeOffice::create($input);
    //         EmployeeCategory::create($input);
    //         EmployeeDepartment::create($input);
    //         EmployeeSalary::create($input);
    //         EmployeeProject::create($input);
            
    //         if($input['hr_grade_id'] !=''){
    //             EmployeeGrade::create($input);
    //         }
    //     }
        
    // }
       // echo "data Successfully update";

   // }


    public function create(){
        session()->put('hr_employee_id', '');
    	$genders = Gender::all();
    	$maritalStatuses = MaritalStatus::all();
    	$religions = Religion::all();
        
    	return view ('hr.employee.create', compact('genders','maritalStatuses','religions'));
    }


    public function store (EmployeeStore $request){
       
    	 $input = $request->all();
            if($request->filled('date_of_birth')){
            $input ['date_of_birth']= \Carbon\Carbon::parse($request->date_of_birth)->format('Y-m-d');
            }
            if($request->filled('cnic_expiry')){
            $input ['cnic_expiry']= \Carbon\Carbon::parse($request->cnic_expiry)->format('Y-m-d');
            }
            
            $input ['hr_status_id']=HrStatus::where('name','On Board')->first()->id;

            $employee='';
    	DB::transaction(function () use ($input, &$employee) {  

    		$employee = HrEmployee::create($input);

    	}); // end transcation

        //return response()->json(['url'=>url('/dashboard')]);
        
    	return response()->json(['url'=> route("employee.edit",$employee),'message' => 'Data Successfully Saved']);
    }

    public function index(){
    	$employees = HrEmployee::with('employeeDesignation','hrContactMobile','employeeAppointment')->get();

       
    	return view ('hr.employee.list',compact('employees'));
    }

    public function allEmployeeList(){
        $employees = HrEmployee::all();

        return view ('hr.employee.allEmployeeList',compact('employees'));
    }

    public function missingDocuments(){

        $employees = HrEmployee::with('documentName')->get();

        return view ('hr.employee.missingDocuments', compact('employees'));
    }

    public function edit(Request $request, $id){
        
    	$genders = Gender::all();
    	$maritalStatuses = MaritalStatus::all();
    	$religions = Religion::all();
    	$data = HrEmployee::find($id);
        session()->put('hr_employee_id', $data->id);
      
        if($request->ajax()){      
            return view ('hr.employee.ajax', compact('genders','maritalStatuses','religions','data'));    
        }else{
            return view ('hr.employee.edit', compact('genders','maritalStatuses','religions','data'));       
        }
    }


    public function update(EmployeeStore $request, $id){
        
        //ensure client end is is not changed
        if($id != session('hr_employee_id')){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }

    	$input = $request->all();
            if($request->filled('date_of_birth')){
            $input ['date_of_birth']= \Carbon\Carbon::parse($request->date_of_birth)->format('Y-m-d');
            }
            if($request->filled('cnic_expiry')){
            $input ['cnic_expiry']= \Carbon\Carbon::parse($request->cnic_expiry)->format('Y-m-d');
            }

    		DB::transaction(function () use ($input, $id) {  

    		HrEmployee::findOrFail($id)->update($input);

    		}); // end transcation
        
        if($request->ajax()){
    	return response()->json(['status'=> 'OK', 'message' => "Data Successfully Updated"]);
        }else{
            return back()->with('message', 'Data Successfully Updated');
        }
    }

    public function destroy($id)
    {   
        
        //ensure client end is is not changed
        if($id != session('hr_employee_id')){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }

        HrEmployee::findOrFail($id)->delete();

       return back()->with('message', 'Data Successfully Deleted');
        //return response()->json(['status'=> 'OK', 'message' => 'Data Successfully Deleted']);
    }

    public function employeeCnic(Request $request){

        if($request->get('query'))
        {
            $hrEmployee = HrEmployee::where('cnic', $request->get('query'))->get()->first();

            if ($hrEmployee){

                return response()->json(['status'=> 'Not Ok']);
            }else{
                return response()->json(['status'=> 'Ok']);
            }

        }

    }

}
