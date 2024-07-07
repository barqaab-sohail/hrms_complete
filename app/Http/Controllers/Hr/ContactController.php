<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Country;
use App\Models\Common\State;
use App\Models\Common\City;
use App\Models\Hr\HrContactType;
use App\Models\Hr\HrContact;
use App\Models\Hr\HrContactMobile;
use App\Models\Hr\HrContactLandline;
use App\Models\Hr\HrContactEmail;
use App\Http\Requests\Hr\ContactStore;
use DataTables;
use DB;

class ContactController extends Controller
{
    public function __construct()
    {
        // $this->middleware('EditContactPermission')->only('update', 'store','create','edit');
    }


    // public function show(Request $request, $id){

    // 	$countries = Country::all();
    // 	$hrContactTypes = HrContactType::all();

    // 	$hrContacts =  HrContact::where('hr_employee_id', $id)->get();

    //     if($request->ajax()){
    //         return view('hr.contact.create', compact('countries','hrContactTypes','hrContacts','id'));
    //     }else{
    //         return back()->withError('Please contact to administrator, SSE_JS');
    //     }
    // }


    public function store (ContactStore $request){

        $input = $request->all();
   
        DB::transaction(function () use ($input) {  
            
           
            HrContact::updateOrCreate(['id' => $input['contact_id']],
                    
                    ['consumable_id'=> $input['consumable_id'],
                    'unit_id'=> $input['unit_id'],
                    'consumable_cost'=> $input['consumable_cost'],
                    'consumable_qty'=> $input['consumable_qty'],
                    'consumable_date'=> $input['consumable_date'],
                    'asset_id'=> $input['asset_id']]); 
   
        }); // end transcation      
       return response()->json(['success'=>"Data saved successfully."]);
   
    }





    // public function store (ContactStore $request){
    // 	$input = $request->all();
    // 	DB::transaction(function () use ($input, $request) {  
    // 		$hrContact = HrContact::create($input);

    // 		$input['hr_contact_id']=$hrContact->id;
    // 		HrContactMobile::create($input);

    // 		if($request->filled('landline')){
    //         	HrContactLandline::create($input);
    //         }
    		
    //         if($request->filled('email')){
    // 			HrContactEmail::create($input);
    // 		}


    // 	}); // end transcation

    // 	return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);

    // }

    public function create(Request $request){
return $request->hrEmployeeId;
        $hrContacts= HrContact::where('hr_employee_id', $request->hrEmployeeId)
        ->latest()->get();
        $countries = Country::all();    
        $hrContactTypes = HrContactType::all();

        if ($request->ajax()) {
            $data= HrContact::where('hr_employee_id', $request->hrEmployeeId)
            ->latest()->get();
            return  DataTables::of($data)
                    ->addIndexColumn()  
                    ->editColumn('contact_type_id', function($row){                
                           
                      return $row->hrContactType->name;
                          
                   })
                   ->addColumn('address', function($row){                
                           
                       return $row->complete_address;
                           
                    })
                    ->addColumn('mobile', function($row){                
                           
                        return $row->mobile->mobile;
                            
                     })
                     ->addColumn('email', function($row){                
                           
                        return $row->email->email;
                            
                     })
                    ->addColumn('Edit', function($row){
                       
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editContact">Edit</a>';
                                             
                            return $btn;
                    })
                    ->addColumn('Delete', function($row){                
                        
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteContact">Delete</a>';
                                  
                            return $btn;
                    })
                
                    ->rawColumns(['Edit','Delete'])
                    ->make(true);
          
        }
        
        $view =  view('hr.contact.create', compact('countries','hrContactTypes','hrContacts'))->render();
        return response()->json($view);
   
    }

    public function edit($id)
    {
        $contact = HrContact::find($id);
        return response()->json($contact);
    }

    // public function edit (Request $request, $id){
    //     //For security checking
    //     session()->put('contact_edit_id', $id);

    // 	$data = HrContact::find($id);
    // 	$hrContactTypes = HrContactType::all();
    // 	$hrContacts =  HrContact::where('hr_employee_id', session('hr_employee_id'))->get();
    // 	$countries = country::all();
	// 	$states = state::where('country_id', $data->country_id)->get();
	// 	$cities = city::where('state_id', $data->state_id)->get();

    //     if($request->ajax()){
    //         return view('hr.contact.edit', compact('countries','hrContactTypes','hrContacts','data','states','cities'));
    //     }else{
    //         return back()->withError('Please contact to administrator, SSE_JS');
    //     }

    // }

    public function update (ContactStore $request, $id){
        //ensure client end id is not changed
        if($id != session('contact_edit_id')){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }

    	$input = $request->all();
    	DB::transaction(function () use ($input, $request, $id) {  

    		$hrContact = HrContact::findOrFail($id)->update($input);
    					HrContactMobile::where('hr_contact_id',$id)->first()->update($input);
    					if($request->filled('landline')){
    					HrContactLandline::updateOrCreate(['hr_contact_id'=>$id],$input);
    					}
    					if($request->filled('email')){
    					HrContactEmail::updateOrCreate(['hr_contact_id'=>$id],$input);
    					}

    	}); // end transcation
    	$hrContacts =  HrContact::where('hr_employee_id', session('hr_employee_id'))->get();
    	return response()->json(['status'=> 'OK', 'message' => "Data Successfully Updated", 'dataTable'=>$hrContacts]);
    }


    // public function destroy(Request $request, $id){
    //     if(!in_array($id, session('contact_delete_ids'))){
    //         return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
    //     }
    	
    // 	HrContact::findOrFail($id)->delete(); 

    // 	return response()->json(['status'=> 'OK', 'message' => "Data Successfully Deleted"]);
    // }

    public function destroy ($id){
        DB::transaction(function () use ($id) {  
            HrContact::find($id)->delete(); 
        }); // end transcation 
        return response()->json(['success'=>"data  delete successfully."]);
    }

    public function refreshTable($id){
        $hrContacts =  HrContact::where('hr_employee_id', $id)->get();
        $contactIds = $hrContacts->pluck('id')->toArray();
        //For security checking
        session()->put('contact_delete_ids', $contactIds);

        return view('hr.contact.list',compact('hrContacts'));
    }
}
