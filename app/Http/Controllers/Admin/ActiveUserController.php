<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Session;
use Stevebauman\Location\Facades\Location;
use App\User;
use DB;
use DataTables;

class ActiveUserController extends Controller
{



	public function index()
	{
		// Get time session life time from config.
		$time =  time() - (config('session.lifetime') * 60);

		// Total login users (user can be log on 2 devices will show once.)
		$totalActiveUsers = Session::where('last_activity', '>=', $time)->count(DB::raw('DISTINCT user_id'));

		$activeUsers = DB::table('hr_employees')
			->join('users', 'hr_employees.user_id', '=', 'users.id')
			->join('sessions', 'users.id', '=', 'sessions.user_id')
			->select('users.id AS userId', 'users.email', 'users.last_login_at', 'users.last_login_ip', 'sessions.*', 'hr_employees.*')->where('last_activity', '>=', $time)->get();

		return view('admin.activeUser.list', compact('activeUsers', 'totalActiveUsers'));
	}

	public function lastLogin(Request $request)
	{
		if ($request->ajax()) {
			$data = User::orderBy('id', 'desc')->get();

			return DataTables::of($data)

				->addColumn('full_name', function ($data) {
					return $data->hrEmployee->full_name ?? '';
				})
				->addColumn('cnic', function ($data) {
					return $data->hrEmployee->cnic ?? '';
				})
				->addColumn('father_name', function ($data) {
					return $data->hrEmployee->father_name ?? '';
				})
				->addColumn('location', function ($data) {
					if ($data->last_login_ip) {
						$location = Location::get($data->last_login_ip);
						return $location->cityName  ?? '';
					} else {
						return '';
					}
				})
				->rawColumns(['full_name', 'cnic', 'father_name', 'location'])
				->make(true);
		}
		// $ip = '39.42.113.71';
		// $position = Location::get($ip);
		// dd($position);
		return view('admin.activeUser.lastLoginDetail');
	}

	public function logoutAll($id = 1)
	{

		if ($id == 1) {
			$loginUsers = Session::where('user_id', '!=', 1)->get();
			foreach ($loginUsers as $loginUser) {

				$loginUser->delete();
			}
			return response()->json(['status' => 'OK', 'message' => "All Users Logout Successfully"]);
		} else {

			$user = Session::where('user_id', $id)->first();
			if ($user != null) {

				$user->update(array('user_id' => Null));

				return response()->json(['status' => 'OK', 'message' => "User Logout Successfully"]);
			} else {
				return response()->json(['status' => 'Not OK', 'message' => "This User is not Login"]);
			}
		}
	}
}
