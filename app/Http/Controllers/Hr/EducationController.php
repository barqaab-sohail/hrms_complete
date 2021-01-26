<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Education;
use App\Models\Common\Country;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrEducation;
use App\Http\Requests\Hr\EducationStore;
use DB;

class EducationController extends Controller
{
    
    public function create(Request $request){

    	$degrees = Education::all();
    	$countries = Country::all();
    	$hrEducations = HrEducation::where('hr_employee_id',session('hr_employee_id'))->get();

	    if($request->ajax()){
	    	$view =  view('hr.education.create',compact('degrees','countries','hrEducations'))->render();
	    	return response()->json($view);

		}else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }
    }

    public function store(EducationStore $request){

            $input = $request->all();
            $input['hr_employee_id']=session('hr_employee_id');

            DB::transaction(function () use ($input) {  
                HrEducation::create($input);
            }); // end transcation

    		
            return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);
    }


    public function edit(Request $request, $id){
        //For security checking
        session()->put('education_edit_id', $id);


    	$degrees = Education::all();
    	$countries = Country::all();
        $hrEducations = HrEducation::where('hr_employee_id',session('hr_employee_id'))->get();
    	$data = HrEducation::find($id);


    	if($request->ajax()){
	    
            $view =  view('hr.education.edit',compact('degrees','countries','hrEducations','data'))->render();
            return response()->json($view);
		}else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }

    }

    public function update(EducationStore $request, $id){
         //ensure client end id is not changed
        if($id != session('education_edit_id')){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breatch"]);
        }


        $input = $request->all();
        $uniqueEducation=true;
        $degreeNames = HrEducation::where('hr_employee_id', session('hr_employee_id'))
                                ->where('id','!=',$id)
                                ->get();


        foreach($degreeNames as $degreeName){
            $combineDegree = $degreeName->hr_employee_id.$degreeName->education_id;
            $combineRequest = session('hr_employee_id').$request->education_id;

            if($combineDegree == $combineRequest){
                $uniqueEducation=false;
            }
        }

        if($uniqueEducation){
        DB::transaction(function () use ($input, $id) {  

            HrEducation::findOrFail($id)->update($input);

        }); // end transcation
        
        return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);
        }else{
            return response()->json(['status'=> 'Not OK', 'message' => "This degree is already saved"]);
        }
    }





    public function destroy($id){
        
    
        HrEducation::find($id)->delete();

       
        return response()->json(['status'=> 'OK', 'message' => 'Data Sucessfully Deleted']);
    }



    public function refreshTable(){

    	$hrEducations = HrEducation::where('hr_employee_id',session('hr_employee_id'))->get();
       
        return view('hr.education.list',compact('hrEducations'));
        
    }
}
