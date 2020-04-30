<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Session;
use DB;

class ActiveUserController extends Controller
{
    


    public function index(){
	     // Get time session life time from config.
	     $time =  time() - (config('session.lifetime')*60); 

	     // Total login users (user can be log on 2 devices will show once.)
	     $totalActiveUsers = Session::where('last_activity','>=', $time)->
	     count(DB::raw('DISTINCT user_id'));

	    $activeUsers = DB::table('hr_employees')
	                    ->join('users','hr_employees.user_id','=','users.id')
	                    ->join('sessions','users.id','=','sessions.user_id')
	                    ->select('users.id AS userId','users.email','sessions.*','hr_employees.*')->where('last_activity','>=', $time)->get();

	return view ('admin.activeUser.list',compact('activeUsers', 'totalActiveUsers'));

	}



}
