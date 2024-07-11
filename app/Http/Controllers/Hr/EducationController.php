<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Education;
use App\Models\Common\Country;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrEducation;
use App\Http\Requests\Hr\EducationStore;
use DataTables;
use DB;

class EducationController extends Controller
{
    
    public function show(Request $request, $id){

    	$degrees = Education::all();
    	$countries = Country::all();
    	$hrEducations = HrEducation::where('hr_employee_id',$id)->get();

        if($request->ajax()){
            return  view('hr.education.create',compact('degrees','countries','hrEducations'));
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }
    }

    public function create(Request $request){

        if ($request->ajax()) {
          $data= HrEducation::where('hr_employee_id', $request->hrEmployeeId)
          ->latest()->get();
          return  DataTables::of($data)
                  ->addIndexColumn()  
                  ->editColumn('education_id', function($row){                
                         
                    return $row->education->degree_name;
                        
                 })
                 ->addColumn('Edit', function($row){
                     
                         $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editEducation">Edit</a>';
                                           
                          return $btn;
                  })
                  ->addColumn('Delete', function($row){                
                      
                         $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteEducation">Delete</a>';
                                
                          return $btn;
                  })
              
                  ->rawColumns(['Edit','Delete'])
                  ->make(true);
        
      }
 
  }


    // public function create(Request $request){

    // 	$degrees = Education::all();
    // 	$countries = Country::all();
    // 	$hrEducations = HrEducation::where('hr_employee_id',session('hr_employee_id'))->get();

	//     if($request->ajax()){
	//     	$view =  view('hr.education.create',compact('degrees','countries','hrEducations'))->render();
	//     	return response()->json($view);

	// 	}else{
    //         return back()->withError('Please contact to administrator, SSE_JS');
    //     }
    // }

    public function store(EducationStore $request){

            $input = $request->all();
           
            DB::transaction(function () use ($input) {  
            
                $hrContact = HrEducation::updateOrCreate(['id' => $input['hr_education_id']],
                        
                        [
                        'education_id'=> $input['education_id'],
                        'country_id'=> $input['country_id'],
                        'major'=> $input['major'],
                        'institute'=> $input['institute'],
                        'from'=> $input['from'],
                        'to'=> $input['to'],
                        'total_marks'=> $input['total_marks'],
                        'marks_obtain'=> $input['marks_obtain'],
                        'grade'=> $input['grade'],
                        'hr_employee_id'=> $input['hr_employee_id'],
                       ]); 
            }); // end transcation    

    		
            return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);
    }



    public function edit($id)
    {
        $education = HrEducation::with('education')->find($id);
        return response()->json($education);
    }

    public function destroy($id){
        
        DB::transaction(function () use ($id) {         
        
            HrEducation::find($id)->delete();
        }); // end transcation 

        return response()->json(['status'=> 'OK', 'message' => 'Education Successfully Deleted']);
    }


    // public function edit(Request $request, $id){
    //     //For security checking
    //     session()->put('education_edit_id', $id);


    // 	$degrees = Education::all();
    // 	$countries = Country::all();
    //     $hrEducations = HrEducation::where('hr_employee_id',session('hr_employee_id'))->get();
    // 	$data = HrEducation::find($id);


    // 	if($request->ajax()){
	    
    //         $view =  view('hr.education.edit',compact('degrees','countries','hrEducations','data'))->render();
    //         return response()->json($view);
	// 	}else{
    //         return back()->withError('Please contact to administrator, SSE_JS');
    //     }

    // }

    // public function update(EducationStore $request, $id){
    //      //ensure client end id is not changed
    //     if($id != session('education_edit_id')){
    //         return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
    //     }


    //     $input = $request->all();
    //     $uniqueEducation=true;
    //     $degreeNames = HrEducation::where('hr_employee_id', session('hr_employee_id'))
    //                             ->where('id','!=',$id)
    //                             ->get();


    //     foreach($degreeNames as $degreeName){
    //         $combineDegree = $degreeName->hr_employee_id.$degreeName->education_id;
    //         $combineRequest = session('hr_employee_id').$request->education_id;

    //         if($combineDegree == $combineRequest){
    //             $uniqueEducation=false;
    //         }
    //     }

    //     if($uniqueEducation){
    //     DB::transaction(function () use ($input, $id) {  

    //         HrEducation::findOrFail($id)->update($input);

    //     }); // end transcation
        
    //     return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);
    //     }else{
    //         return response()->json(['status'=> 'Not OK', 'message' => "This degree is already saved"]);
    //     }
    // }









    public function refreshTable(){

    	
        $hrEducations = HrEducation::where('hr_employee_id',session('hr_employee_id'))->orderby('to','desc')->get();

        $educationIds = $hrEducations->pluck('id')->toArray();
        //For security checking
        session()->put('education_delete_ids', $educationIds);

        return view('hr.education.list',compact('hrEducations'));
        
    }
}
