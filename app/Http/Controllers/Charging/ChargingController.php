<?php

namespace App\Http\Controllers\Charging;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChargingController extends Controller
{
    public function create(){

    	return view('charging.create');
    }
}
