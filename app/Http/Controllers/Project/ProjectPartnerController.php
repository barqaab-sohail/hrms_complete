<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrRole;
use App\Models\Common\Partner;
use App\Models\Project\PrPartner;
use App\Models\Project\PrPartnerContact;
use App\Http\Requests\Project\PrPartnerStore;
use DB;
use DataTables;


class ProjectPartnerController extends Controller
{
    public function index(){
    	$prRoles = PrRole::all();
		$partners = Partner::all();
        $view =  view ('project.partner.create', compact('prRoles','partners'))->render();
        return response()->json($view);

    }
	public function create(Request $request){

		 if ($request->ajax()) {
            
            $data =  PrPartner::where('pr_detail_id', session('pr_detail_id'))->get();

            return DataTables::of($data)
                    
                    ->editColumn('partner_id', function($row){
   
                        return $row->partner->name;
                    })
                    ->editColumn('pr_role_id', function($row){
   
                        return $row->prRole->name;
                    })

                    ->addColumn('Edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editPrPartner">Edit</a>';
                            return $btn;
                    })
                    ->addColumn('Delete', function($row){
   
                                                     
                           $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletePrPartner">Delete</a>';
                            
                            return $btn;
                    })
                    ->rawColumns(['Edit','Delete'])
                    ->make(true);
                        
        }


	}

	public function store (PrPartnerStore $request){

		$input = $request->all();
    	$input['pr_detail_id'] = session('pr_detail_id');

    	DB::transaction(function () use ($input, $request) {  
            PrPartner::updateOrCreate(['id' => $request->pr_partner_id],
                  $input);
    	}); // end transcation

        return response()->json(['success'=>'Data saved successfully.']);

	}

	public function edit ($id){
    	$data = PrPartner::find($id);
    	return response()->json($data);
    }

    public function destroy($id){
    	
    	PrPartner::findOrFail($id)->delete(); 
    	return response()->json(['status'=> 'OK', 'message' => 'Data Successfully Deleted']);
    }






}
