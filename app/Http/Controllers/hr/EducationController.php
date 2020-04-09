<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    
    public function create(Request $request){

    //return View::make('hr.education.create')
    //->render();
     if($request->ajax()){
    return view('hr.education.create');
	}
    }
}
