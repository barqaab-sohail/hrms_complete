<?php

namespace App\Http\Controllers\Self;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Hr\HrEmployee;
use App\Models\Self\SsContact;
use App\Models\Self\SsContactMobile;
use App\Models\Self\SsContactEmail;
use App\Models\Self\SsContactOffice;
use DB;

class SelfContactController extends Controller
{
    

    public function create(){

    	$employee = HrEmployee::where('user_id', Auth::user()->id)->first();
        $contacts =  SsContact::where('hr_employee_id', $employee->id)->get();
    	return view ('self.contact.create',compact('contacts'));

    }

    public function store(Request $request){

    	$input = $request->all();
    	$employee = HrEmployee::where('user_id', Auth::user()->id)->first();
    	$input['hr_employee_id']= $employee->id;


    	DB::transaction(function () use ($request, $input) {   



    		$SsContact=SsContact::create($input);

    		//add mobile	
            if($request->filled("mobile.0")){
    			for ($i=0;$i<count($request->input('mobile'));$i++){
    			$mobile['mobile']= $request->input("mobile.$i");
    			$mobile['ss_contact_id'] = $SsContact->id;
    			SsContactMobile::create($mobile);		
    			}
            }

			//add email	
            if($request->filled("email.0")){
    			for ($i=0;$i<count($request->input('email'));$i++){
    			$email['email']= $request->input("email.$i");
    			$email['ss_contact_id'] = $SsContact->id;
    			SsContactEmail::create($email);		
    			}
            }

            if($request->filled("office_phone")||$request->filled("office_address")||$request->filled("office_fax")){
    			$input['ss_contact_id']= $SsContact->id;
    			SsContactOffice::create($input);
            }


    	});  //end transaction

		return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);


    }

    public function edit(Request $request, $id){

        $result = SsContact::find($id);
        
        if($request->ajax()){

            return response()->json($result);
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }

    }


    public function destroy($id){
        
        SsContact::findOrFail($id)->delete(); 

        return response()->json(['status'=> 'OK', 'message' => 'Data Sucessfully Deleted']);
    }




    public function refreshTable(){
    	$employee = HrEmployee::where('user_id', Auth::user()->id)->first();
        $contacts =  SsContact::where('hr_employee_id', $employee->id)->get();
        return view('self.contact.list',compact('contacts'));
    }



}
