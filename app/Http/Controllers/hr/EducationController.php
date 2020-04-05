<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    
    public function create(){

    //return View::make('hr.education.create')
    //->render();

    return view('hr.education.create');
    }
}
