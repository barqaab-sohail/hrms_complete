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

        $data = SsContact::find($id);
        
        if($request->ajax()){

            $view =  view('self.contact.edit',compact('data'))->render();
            return response()->json($view);
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }

    }

    public function update(Request $request, $id){

        $input = $request->all();

        DB::transaction(function () use ($request, $input, $id, &$test) {  

            //update Contact
            SsContact::findOrFail($id)->update($input);

            //update phone  
            if(count($request->input('mobile'))==SsContactMobile::where('ss_contact_id',$id)->count())
            {       $ssContactMobile = SsContactMobile::where('ss_contact_id',$id)->get();
                    foreach($request->input('mobile') as $key => $num){
                        
                        $mobileId = $ssContactMobile->get($key)->id;
                        SsContactMobile::findOrFail($mobileId)->update(['mobile'=>$num]);
                    }
              
            }elseif(count($request->input('mobile'))<SsContactMobile::where('ss_contact_id',$id)->count()){

                    $ssContactMobile = SsContactMobile::where('ss_contact_id',$id)->get();
                    $count = 0;
                    foreach($request->input('mobile') as $key => $num){
                        
                        $mobileId = $ssContactMobile->get($key)->id;
                        SsContactMobile::findOrFail($mobileId)->update(['mobile'=>$num]);
                        $count++;
                    }

                    $ssContactMobile->get($count)->delete(); // Remaining data delete from database
                   
                    
            }elseif(count($request->input('mobile'))>SsContactMobile::where('ss_contact_id',$id)->count()){

                $ssContactMobile = SsContactMobile::where('ss_contact_id',$id)->get();
                foreach($request->input('mobile') as $key => $num){
                        $mobileId = $ssContactMobile->get($key)->id??''; // this is required becuase greater value is not exist in database so greater value must be optional
                        SsContactMobile::updateOrCreate(
                        ['ss_contact_id' => $id, 'id'=> $mobileId],
                        ['mobile'=>$num]);
                }

            } //end update phone

             //update email
            if ($request->filled("email.0")){
                if(count($request->input('email'))==SsContactEmail::where('ss_contact_id',$id)->count())
                {       $ssContactEmail = SsContactEmail::where('ss_contact_id',$id)->get();
                        foreach($request->input('email') as $key => $num){
                            
                            $emailId = $ssContactEmail->get($key)->id;
                            SsContactEmail::findOrFail($emailId)->update(['email'=>$num]);
                        }
                  
                }elseif(count($request->input('email'))<SsContactEmail::where('ss_contact_id',$id)->count()){

                        $ssContactEmail = SsContactEmail::where('ss_contact_id',$id)->get();
                        $count = 0;
                        foreach($request->input('email') as $key => $num){
                            
                            $emailId = $ssContactEmail->get($key)->id;
                            SsContactEmail::findOrFail($emailId)->update(['email'=>$num]);
                            $count++;
                        }

                        $ssContactEmail->get($count)->delete(); // Remaining data delete from database
                       
                        
                }elseif(count($request->input('email'))>SsContactEmail::where('ss_contact_id',$id)->count()){

                    $ssContactEmail = SsContactEmail::where('ss_contact_id',$id)->get();
                    foreach($request->input('email') as $key => $num){
                            $emailId = $ssContactEmail->get($key)->id??''; // this is required becuase greater value is not exist in database so greater value must be optional
                            SsContactEmail::updateOrCreate(
                            ['ss_contact_id' => $id, 'id'=> $emailId],
                            ['email'=>$num]);
                    }

                }
            }else{
                SsContactEmail::where('ss_contact_id',$id)->delete();
                
            }//end update email

            //update office detail
            if($request->filled("office_phone")||$request->filled("office_address")||$request->filled("office_fax")){
                
                $SsContactOffice = SsContactOffice::where('ss_contact_id',$id)->first();

                SsContactOffice::updateOrCreate(
                        ['ss_contact_id' => $id, 'id'=> $SsContactOffice->id??''],
                        $input);          
            }else{
                $SsContactOffice = SsContactOffice::where('ss_contact_id',$id)->delete();
            }
           
        }); //end transaction

        //$input = json_encode($input["mobile"][1]);
        return response()->json(['status'=> 'OK', 'message' => "$test Data Sucessfully Updated"]);

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
