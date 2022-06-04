<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Common\Gender;
use App\Models\Common\MaritalStatus;
use App\Models\Common\Religion;
use App\Models\Common\BloodGroup;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrEducation;
use App\Models\Hr\HrDesignation;
use App\Models\Hr\EmployeeAppointment;
use App\Models\Hr\EmployeeCategory;
use App\Models\Hr\HrCategory;
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
use App\Models\Common\Education;
use App\Models\Project\PrDetail;
use App\Models\Hr\HrDocumentation;
use DB;
use App\Http\Requests\Hr\EmployeeStore;
use DataTables;

class EmployeeController extends Controller
{
   

    public function create(){

      session()->put('hr_employee_id', '');
    	$genders = Gender::all();
    	$maritalStatuses = MaritalStatus::all();
    	$religions = Religion::all();
       
        // check variable size
        // $data = HrEmployee::where('hr_status_id',1)->take(50)->get();
        // $serializedFoo = serialize($data);
        // $size = mb_strlen($serializedFoo, '8bit');
        // dd($size);

    	return view ('hr.employee.create', compact('genders','maritalStatuses','religions'));
    }

    public function card(){
        
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
            $input ['employee_no'] = generateEmployeeId();

            $employee='';
    	DB::transaction(function () use ($input, &$employee) {  

    		$employee = HrEmployee::create($input);

    	}); // end transcation
        
    	return response()->json(['url'=> route("employee.edit",$employee),'message' => 'Data Successfully Saved']);
    }

    public function index(Request $request){

   
        if($request->ajax()){
            $data = HrEmployee::with('employeeDesignation','employeeProject','employeeOffice','employeeAppointment','hrContactMobile')->get();   

            //first sort with respect to Designation
            $designations = employeeDesignationArray();
            $data = $data->sort(function ($a, $b) use ($designations) {
              $pos_a = array_search($a->designation??'', $designations);
              $pos_b = array_search($b->designation??'', $designations);
                if($pos_a &&  $pos_b){
                    return $pos_a - $pos_b;
                }else{
                    return -1;
                }
            });

            //second sort with respect to Hr Status
            $hrStatuses = array('On Board','Resigned','Terminated','Retired','Long Leave','Manmonth Ended','Death');

            $data = $data->sort(function ($a, $b) use ($hrStatuses) {
              $pos_a = array_search($a->hr_status_id??'', $hrStatuses);
              $pos_b = array_search($b->hr_status_id??'', $hrStatuses);
              return $pos_a - $pos_b;
            });


            return DataTables::of($data)

            ->addColumn('full_name',function($data){
                
                if(Auth::user()->can('hr edit record')){
                    $fullName = '<a href="'.route('employee.edit',$data->id).'" style="color:grey">'.$data->full_name.'</a>';
                    return $fullName;
                }else{
                    return $data->full_name;
                }

            })

            ->addColumn('project',function($data){
                
               $project = isset($data->employeeProject->last()->name)?$data->employeeProject->last()->name:'';
               if($project==''){
                    return '';
               }
               else if($project =='overhead'){
                    return $data->employeeOffice->last()->name??'';

                }else{

                    if(Auth::user()->hasPermissionTo('hr edit documentation')){
                    $link = '<a href="'.route('project.edit',$data->employeeProject->last()->id??'').'" style="color:grey">'.$data->employeeProject->last()->name??''.'</a>';
                     return $link;
                    } else{
                        return $data->employeeProject->last()->name??'';
                    }

                }

            })
            ->addColumn('date_of_birth',function($data){
                return \Carbon\Carbon::parse($data->date_of_birth)->format('M d, Y');
            })
            ->addColumn('date_of_joining',function($data){
                return \Carbon\Carbon::parse($data->employeeAppointment->joining_date??'')->format('M d, Y');
            })
            ->addColumn('mobile',function($data){
                return $data->hrContactMobile->mobile??'';
            })
            ->addColumn('edit', function($data){

                    if(Auth::user()->hasPermissionTo('hr edit documentation')){
                        
                        $button = '<a class="btn btn-success btn-sm" href="'.route('employee.edit',$data->id).'"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>';

                        return $button;
                    } 

            })
            ->addColumn('delete', function($data){
                        $button = '<form  id="formDeleteContact'.$data->id.'"  action="'.route('employee.destroy',$data->id).'" method="POST">'.method_field('DELETE').csrf_field().'
                                 <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you Sure to Delete\')" href= data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-trash-alt"></i></button>
                                 </form>';
                        return $button;
                    })

            ->rawColumns(['full_name','project','date_of_birth','date_of_joining','mobile','edit','delete'])
            ->make(true);
        }
          
        return view ('hr.employee.listDataTable');
            
    
    }

    public function activeEmployeesList(){
        $employees = HrEmployee::where('hr_status_id',1)->with('employeeDesignation','hrContactMobile','employeeAppointment')->get();


        return view ('hr.employee.activeEmployeesList',compact('employees'));
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

     public function userData($id){

        $genders = Gender::all();
        $maritalStatuses = MaritalStatus::all();
        $religions = Religion::all();
        $data = HrEmployee::find($id);
        session()->put('hr_employee_id', $data->id);
      

        return view ('hr.employee.edit', compact('genders','maritalStatuses','religions','data'));       
       
    }

    public function search(){

        $categories = HrCategory::all();
        $bloodGroups = BloodGroup::all();
        $degrees = Education::all();
        $designations = HrDesignation::all();
        $projects = PrDetail::select('id','name','project_no')->get();
       
        $managerIds = EmployeeManager::all()->pluck('hr_manager_id')->toArray();
        $managers = HrEmployee::where('hr_status_id',1)->wherein('id',$managerIds)->with('employeeDesignation')->get(['id','first_name','last_name']);

        $employees = HrEmployee::select('id','first_name','last_name')->with('employeeDesignation')->get();

        return view ('hr.employee.search.search',compact('categories','degrees','designations','projects','managers','employees','bloodGroups'));
    }


    public function result(Request $request){
        
       
        if($request->filled('category')){
        $result = collect(HrEmployee::join('employee_categories','employee_categories.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_categories.hr_category_id','employee_categories.effective_date as cat')->whereIn('hr_status_id',array(1,5))->orderBy('cat','desc')->get());
            $resultUnique = ($result->unique('id'));
            $resultUnique->values()->all();
            $result = $resultUnique->where('hr_category_id',$request->category);
        return view('hr.employee.search.result',compact('result'));
        }

        if($request->filled('designation')){
        $result = collect(HrEmployee::join('employee_designations','employee_designations.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_designations.hr_designation_id','employee_designations.effective_date')->where('hr_status_id',1)->orderBy('effective_date','desc')->get());
            $resultUnique = ($result->unique('id'));
            $resultUnique->values()->all();
            $result = $resultUnique->where('hr_designation_id',$request->designation);
        return view('hr.employee.search.result',compact('result'));
        }

        if($request->filled('degree')){
            
            $data = $request->all();

            $result = HrEmployee::join('hr_educations','hr_educations.hr_employee_id','hr_employees.id')->select('hr_employees.*','hr_educations.education_id','hr_educations.to')
            ->when($data['degree'], function ($query) use ($data){
                                return $query->where('education_id','=',$data['degree']);
                                })

            ->where('hr_status_id',1)->get();

            return view('hr.employee.search.result',compact('result'));

        }

        if($request->filled('project')){
            
            $result = collect(HrEmployee::join('employee_projects','employee_projects.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_projects.pr_detail_id','employee_projects.effective_date')->where('hr_status_id',1)->orderBy('effective_date','desc')->get());
            $resultUnique = ($result->unique('id'));
            $resultUnique->values()->all();
            $result = $resultUnique->where('pr_detail_id',$request->project);
        return view('hr.employee.search.result',compact('result'));

        }

        if($request->filled('manager')){
            
            $result = collect(HrEmployee::join('employee_managers','employee_managers.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_managers.hr_manager_id','employee_managers.effective_date')->where('hr_status_id',1)->orderBy('effective_date','desc')->get());
            $resultUnique = ($result->unique('id'));
            $resultUnique->values()->all();
            $result = $resultUnique->where('hr_manager_id',$request->manager);
            

        return view('hr.employee.search.result',compact('result'));
        }

        if($request->filled('blood_group')){
            $result = HrEmployee::join('hr_blood_groups','hr_blood_groups.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','hr_blood_groups.blood_group_id')->where('hr_status_id',1)->where('blood_group_id',$request->blood_group)->get();
        return view('hr.employee.search.result',compact('result'));

        }

        if ($request->filled('document_name')){
           $data = $request->all();
            
            $result = HrDocumentation::with('hrEmployee')
                        ->when($data['document_name'], function ($query) use ($data){
                                return $query-> where('description', 'LIKE', "%{$data['document_name']}%");
                        })
                        ->when($data['employee'],function ($query) use ($data){
                                return $query-> where('hr_employee_id',$data['employee']);
                        })
                    ->get();

            //this variable used only for goting to else part of result blade.
            $documents=false;

           return view('hr.employee.search.result',compact('result','documents'));
       }
        if($request->filled('employee')){
            $result = HrEmployee::where('id',$request->employee)->get();
        return view('hr.employee.search.result',compact('result'));

        }


    }

}
