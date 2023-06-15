<?php

namespace App\Http\Controllers\Self;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MultiplePrintsController extends Controller
{
    public function print()
    {

        return view('self.multiplePrint.create');
    }
}
