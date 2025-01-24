<?php

namespace App\Http\Controllers\Photocopy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PhotocopyController extends Controller
{
    public function index(){

       return view('photocopy.list');
        
    }
}
