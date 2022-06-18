<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Submission\SubDescription;
use DB;

class SubScopeController extends Controller
{
    public function create(){

    	$data = SubDescription::where('submission_id',session('submission_id'))->first();
    	$view =  view('submission.scope.create', compact('data'))->render();
	    return response()->json($view);
    }

    public function update (Request $request, $id){

    	 DB::transaction(function () use ($id, $request) {  
            
            $input = $request->all();
            
            SubDescription::findOrFail($id)->update($input);

        	}); // end transcation

          return response()->json(['status'=> 'OK', 'message' => "Data Successfully Update"]);
    }
}
