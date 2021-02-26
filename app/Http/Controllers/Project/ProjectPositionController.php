<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrPosition;
use App\Models\Project\PrPositionType;
use App\Http\Requests\Project\PrPositionStore;
use DB;


class ProjectPositionController extends Controller
{
    
    public function create(Request $request){

    	$positionTypes = PrPositionType::all();
    	$prPositions =  PrPosition::where('pr_detail_id', session('pr_detail_id'))->get();

        if($request->ajax()){
            $view = view ('project.position.create', compact('positionTypes','prPositions'))->render();
            return response()->json($view);
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }
    }

    public function store (PrPositionStore $request){

    	$input = $request->all();
    	$input['pr_detail_id'] = session('pr_detail_id');

    	DB::transaction(function () use ($input) {  
    		PrPosition::create($input);
    	}); // end transcation

    	return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);

    }

     public function edit (Request $request, $id){
    	$data = PrPosition::find($id);
    	$positionTypes = PrPositionType::all();
    	$prPositions =  PrPosition::where('pr_detail_id', session('pr_detail_id'))->get();

        if($request->ajax()){
            $view = view ('project.position.edit', compact('positionTypes','prPositions','data'))->render();
            return response()->json($view);
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }

    }

    public function update (PrPositionStore $request, $id){
    	$input = $request->all();
    	DB::transaction(function () use ($input, $id) {  

    	 PrPosition::findOrFail($id)->update($input);

    	}); // end transcation
    	
    	return response()->json(['status'=> 'OK', 'message' => "Data Successfully Updated"]);
    }



    public function destroy($id){
    	
    	PrPosition::findOrFail($id)->delete(); 

    	return response()->json(['status'=> 'OK', 'message' => 'Data Successfully Deleted']);
    }



    public function refreshTable(){
        $prPositions =  PrPosition::where('pr_detail_id', session('pr_detail_id'))->get();
        return view('project.position.list',compact('prPositions'));
    }
}
