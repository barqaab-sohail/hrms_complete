<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrExperience;
use App\Models\Common\Country;
use App\Http\Requests\Hr\ExperienceStore;
use DB;

class ExperienceController extends Controller
{
    
    public function create(Request $request){

    	$countries = Country::all();
    	$hrExperiences = HrExperience::where('hr_employee_id',session('hr_employee_id'))->get();

	    if($request->ajax()){
	    	$view =  view('hr.experience.create',compact('countries','hrExperiences'))->render();
	    	return response()->json($view);

		}else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }
    }

    public function store(ExperienceStore $request){

            $input = $request->all();
            $input['hr_employee_id']=session('hr_employee_id');

            DB::transaction(function () use ($input) {  
                HrExperience::create($input);
            }); // end transcation

            
            return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);
    }


    public function edit(Request $request, $id){

        $countries = Country::all();
        $hrExperiences = HrExperience::where('hr_employee_id',session('hr_employee_id'))->get();
        $data = HrExperience::find($id);


        if($request->ajax()){
        
            $view =  view('hr.experience.edit',compact('countries','hrExperiences','data'))->render();
            return response()->json($view);
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }

    }

    public function update(ExperienceStore $request, $id){
        $input = $request->all();
       
        DB::transaction(function () use ($input, $id) {  

            HrExperience::findOrFail($id)->update($input);

        }); // end transcation
        
        return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Updated"]);
        
    }


    public function destroy($id){
        
    
        HrExperience::find($id)->delete();

       
        return response()->json(['status'=> 'OK', 'message' => 'Data Sucessfully Deleted']);
    }


    public function refreshTable(){

       $hrExperiences = HrExperience::where('hr_employee_id',session('hr_employee_id'))->get();
       
        return view('hr.experience.list',compact('hrExperiences'));
        
    }

}
