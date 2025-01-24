<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Project\PrDetail;
use App\Models\Invoice\InvoiceRight;
use App\Http\Requests\Invoice\InvoiceRightStore;
use DB;
use DataTables;

class InvoiceRightsController extends Controller
{
    public function create(Request $request){

    	$projects = PrDetail::all();
    	$employees = HrEmployee::all();

    	return view ('invoice.rights', compact('projects', 'employees'));
    }

    public function store (InvoiceRightStore $request){

        DB::transaction(function () use ($request) {  
        	
           InvoiceRight::create($request->all());
        }); // end transcation      
       return response()->json(['success'=>'Data saved successfully.']);

    }

    public function show(Request $request, $id){
    	if ($request->ajax()) {
            $data = InvoiceRight::where('hr_employee_id',$id)->latest()->with('hrEmployee','prDetail')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('fullName', function($row){                
                      
                           return $row->hrEmployee->first_name.' '.$row->hrEmployee->last_name;
                           
                })
                ->addColumn('projectName', function($row){                
                      
                           return $row->prDetail->name;
                           
                })
                
                ->addColumn('Delete', function($row){                
                  
                       $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteRight">Delete</a>'; 
                        return $btn;
                })       
                ->rawColumns(['Delete'])
                ->make(true);
        }

    }

    public function destroy ($id){

    	DB::transaction(function () use ($id) {  
    		InvoiceRight::find($id)->delete();  		   	
    	}); // end transcation 
        return response()->json(['success'=>'data  delete successfully.']);

    }

}
