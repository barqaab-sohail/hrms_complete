<?php

namespace App\Http\Controllers\Input;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Input\InputMonthStore;
use App\Models\Input\HrInputMonth;
use DB;
use DataTables;

class InputMonthController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
   
        $months = ['January','February', 'March','April', 'May','June','July','August','September','October', 'November', 'December'];
        $years = ['2021','2022'];

        
        if ($request->ajax()) {
            $data = HrInputMonth::latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editMonth">Edit</a>';
   
                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteMonth">Delete</a>';
    
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
      
        return view('input.inputMonth.inputMonth', compact('years','months'));
    }
     
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InputMonthStore $request)
    {
        HrInputMonth::updateOrCreate(['id' => $request->month_id],
                ['month' => $request->month, 'year' => $request->year, 'is_lock' => $request->is_lock]);        
   
        return response()->json(['success'=>'Month saved successfully.']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = HrInputMonth::find($id);
        return response()->json($book);
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        HrInputMonth::find($id)->delete();
     
        return response()->json(['success'=>'Month deleted successfully.']);
    }




















   //  public function create(){
    	
    	
   //  	$months = ['January','February', 'March','April', 'May','June','July','August','September','October', 'November', 'December'];
   //  	$years = ['2021','2022'];

   //  	$monthYears = HrInputMonth::all();
   //  	return view ('input.inputMonth.inputMonth',compact('years','months','monthYears'));
   //  }

   //  public function store (InputMonthStore $request){

   //  	$input = $request->all();
		   	
   //          DB::transaction(function () use ($input, &$data) {  
   //              $data = HrInputMonth::create($input);
   //          }); // end transcation
   //  	return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved", 'data'=>$data]);

   //  }

   //  public function update(InputMonthStore $request, $id){

   //      $input = $request->all();
   //      DB::transaction(function () use ($input, $id) {  

   //          HrInputMonth::findOrFail($id)->update($input);

   //      }); // end transcation
        
   //      $data = HrInputMonth::findOrFail($id);

   //      return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved", 'data'=>$data]);

   //  }

   //  public function destroy($id){
   //  	// Comitted due to casecade delete data
   //  	//HrInputMonth::findOrFail($id)->delete();
   //  	return response()->json('OK');
   // }




}
