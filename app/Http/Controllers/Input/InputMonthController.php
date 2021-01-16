<?php

namespace App\Http\Controllers\Input;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InputMonthController extends Controller
{
    public function create(){
    	
    	
    	$months = ['January','Febrary', 'March','April', 'May','June','July','August','September','October', 'November', 'December'];
    	$years = ['2021','2022'];
 
    	return view ('input.inputMonth.create',compact('years','months'));
    }

    public function store (){

    	
    }
}
