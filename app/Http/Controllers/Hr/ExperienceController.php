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

            
            return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);
    }


    public function edit(Request $request, $id){
        //For security checking
        session()->put('experience_edit_id', $id);

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
        //ensure client end id is not changed
        if($id != session('experience_edit_id')){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }

        $input = $request->all();
       
        DB::transaction(function () use ($input, $id) {  

            HrExperience::findOrFail($id)->update($input);

        }); // end transcation
        
        return response()->json(['status'=> 'OK', 'message' => "Data Successfully Updated"]);
        
    }


    public function destroy($id){
        if(!in_array($id, session('experience_delete_ids'))){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }
    
        HrExperience::find($id)->delete();

       
        return response()->json(['status'=> 'OK', 'message' => 'Data Successfully Deleted']);
    }


    public function refreshTable(){

       $hrExperiences = HrExperience::where('hr_employee_id',session('hr_employee_id'))->orderby('to','desc')->get();
       
       $ids = $hrExperiences->pluck('id')->toArray();
        //For security checking
        session()->put('experience_delete_ids', $ids);

        return view('hr.experience.list',compact('hrExperiences'));
        
    }

    

}
