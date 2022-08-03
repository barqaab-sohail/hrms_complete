<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrMonthlyExpense;
use DB;
use DataTables;


class ProjectMonthlyExpenseController extends Controller
{
    public function index() {
        
        $view =  view('project.expense.create')->render();
        return response()->json($view);

    }

    public function create(Request $request){

        if ($request->ajax()) {
            $data = PrMonthlyExpense::where('pr_detail_id',session('pr_detail_id'))->latest()->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('Edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editExpense">Edit</a>';
                                                     
                            return $btn;
                    })
                    ->addColumn('Delete', function($row){                
                      
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteExpense">Delete</a>';
                            
                           
                            return $btn;
                    })
                    ->editColumn('month', function($row){                
                      
                        return \Carbon\Carbon::parse($row->month)->format('Y-M');

                           
                    })
                    ->editColumn('salary_expense', function($row){                
                      
                           return addComma($row->salary_expense??'');
                           
                    })
                    ->editColumn('non_salary_expense', function($row){                
                      
                           return addComma($row->non_salary_expense??'');
                           
                    })
                    ->editColumn('total_expense', function($row){                
                      
                           return addComma($row->salary_expense + $row->non_salary_expense);
                           
                    })
                    ->rawColumns(['Edit','Delete','total_expense'])
                    ->make(true);
        }

	}

	public function store(Request $request){

		$input = $request->all();
		
        $input['pr_detail_id']=session('pr_detail_id');
        $input ['month']= \Carbon\Carbon::parse($request->month)->format('Y-m-d');
		
		if($request->filled('salary_expense')){
            $input ['salary_expense']= intval(str_replace( ',', '', $request->salary_expense));
        }

        if($request->filled('non_salary_expense')){
            $input ['non_salary_expense']= intval(str_replace( ',', '', $request->non_salary_expense));
        }

     	DB::transaction(function () use ($input, $request) {  

    		PrMonthlyExpense::updateOrCreate(['id' => $input['expense_id']], $input); 


    	}); // end transcation

		return response()->json(['success'=>'Data saved successfully.']);
	}

	public function edit($id){
		$prMonthlyExpense = PrMonthlyExpense::find($id);
        return response()->json($prMonthlyExpense);
	}

	public function destroy($id){

		PrMonthlyExpense::findOrFail($id)->delete();
		return response()->json(['status'=> 'OK', 'message' => "Data Successfully Deleted"]);
		
	}


}
