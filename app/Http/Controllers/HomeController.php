<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        $employees = HrEmployee::find(3);
        return view('dashboard.dashboard', compact('employees'));
    }
    public function testing()
    {
        return view('dashboard.dashboard1');
    }
}
