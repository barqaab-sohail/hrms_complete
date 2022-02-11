<?php

namespace App\Http\Controllers\Input;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDesignation;
use App\Models\Input\InputProject;
use App\Models\Input\InputMonth;
use App\Models\Input\Input;
use App\Http\Requests\Input\InputStore;
use DataTables;
use DB;

class InputController extends Controller
{
    public function create(){

    	$inputMonths = InputMonth::where('is_lock',0)->get();
    	$hrEmployees = HrEmployee::where('hr_status_id',1)->get();
    	$hrDesignations = HrDesignation::all();
      $offices = ['Chief Executive Officer','General Manager (Power)','General Manager (W&C)','Manager (Finance)','HR & Administration'];
    	
      return view ('input.create',compact('inputMonths','hrEmployees','hrDesignations','offices'));

    }

    public function show($id){
        $inputProjects = InputProject::where('input_month_id',$id)->with('prDetail')->get();
        return response()->json($inputProjects);

    }


   public function projectList($id, $month, Request $request){

            
          if ($request->ajax()) {
              
              $data = Input::join('input_projects','inputs.input_project_id','=','input_projects.id')->select('inputs.*','input_projects.input_month_id','input_projects.pr_detail_id','input_projects.is_lock')->where('inputs.input_project_id',$id)->with('hrEmployee','hrDesignation','prDetail')->latest()->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    
                    ->addColumn('full_name', function($row){  
                        return $row->hrEmployee->first_name. ' '.$row->hrEmployee->last_name;
                    })
                    ->addColumn('designation', function($row){  
                        return $row->hrDesignation->name??'';
                    })
                    ->addColumn('input', function($row){  
                        return $row->input;
                    })
                    ->editColumn('remarks', function($row){  
                        return $row->remarks??'';
                    })
                    ->addColumn('edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editInput">Edit</a>';
                            return $btn;
                    })
                    ->addColumn('delete', function($row){
   
                           $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteInput">Delete</a>';
                        
                            return $btn;
                    })

                    ->rawColumns(['no','full_name','designation','input','remarks','edit','delete'])
                    ->make(true);            
          }
    	return response()->json($inputProjects);
   }

   public function store(InputStore $request){

   			
            $inputProject = InputProject::find($request->input_project_id);

            DB::transaction(function () use ($request, $inputProject) {  

                Input::updateOrCreate(['id' => $request->input_id],
                  ['input_project_id' => $request->input_project_id, 
                  'hr_employee_id' => $request->hr_employee_id, 
                  'hr_designation_id' => $request->hr_designation_id,
                  'pr_detail_id' => $inputProject->pr_detail_id,
                  'input' => $request->input,
                  'remarks' => $request->remarks]
                  );
            }); // end transcation
            
        return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);
            
   }

   public function edit($id){
    $book = Input::find($id);
    return response()->json($book);

   }

   
  public function destroy($id)
  {
       Input::findOrFail($id)->delete();
   
      return response()->json(['success'=>'Project deleted successfully.']);
  }

  public function search(){
        $months = InputMonth::all();

        return view ('input.search.search',compact('months'));
  }

  public function result(Request $request){

    $inputProjectIds = InputProject::where('input_month_id',$request->month)->pluck('id')->toArray();

    $result = Input::whereIn('input_project_id',$inputProjectIds)->with('hrEmployee','hrDesignation','prDetail')->orderBy('pr_detail_id','asc')->get();
    $inputProjects = InputProject::where('input_month_id',$request->month)->get('pr_detail_id');
    
     return view('input.search.result',compact('result','inputProjects'));
  }

}
