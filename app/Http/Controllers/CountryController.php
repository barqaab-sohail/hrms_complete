<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CountryController extends Controller
{
    

   public function getStates($id)
	{
	    $states = DB::table("states")
	                ->where("country_id",$id)
	                ->pluck("name","id");
	    
	    return response()->json($states);
	}

	public function getCities($id)
	{
	    $cities = DB::table("cities")
	                ->where("state_id",$id)
	                ->pluck("name","id");
	    
	    return response()->json($cities);
	}


}
