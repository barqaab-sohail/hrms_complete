<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrDesignation;
use App\Http\Requests\Hr\DesignationStore;
use DB;

class DesignationController extends Controller
{
     public function store (DesignationStore $request){
     	$newDesignation = preg_replace('/[^A-Za-z0-9\- ]/', '', $request->name);
        $designation = HrDesignation::where('name', $newDesignation)->first();
       
        if($designation == null){
            
             DB::transaction(function () use ($request, $newDesignation) {  

                 HrDesignation::create(['name'=>$newDesignation, 'level'=>$request->level]);

            }); // end transcation   

            $designations = HrDesignation::all();
        
            return response()->json(['designations'=> $designations, 'message'=>"$newDesignation Successfully Entered"]);
        }else{

            return response()->json(['designations'=> '', 'message'=>"$newDesignation is already entered"]);
           
        }
      
    	
    }
}
