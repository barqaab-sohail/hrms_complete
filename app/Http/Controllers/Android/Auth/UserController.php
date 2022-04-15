<?php

namespace App\Http\Controllers\Android\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDocumentation;

class UserController extends Controller
{
    
    public function login(Request $request)
    {
      
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 

        	
            $auth = Auth::user(); 
            $success['token'] =  $auth->createToken('LaravelSanctumAuth')->plainTextToken;
            $user = User::find(Auth::user()->id);
            $picture = HrDocumentation::where('hr_employee_id',$user->hrEmployee->id)->where('description','picture')->first();
            $success['status'] = 'true';
            $success['email'] = $user->email;
            $success['name'] = $user->hrEmployee->full_name; 
            $success['pictureUrl'] = asset('/storage/'.$picture->path . $picture->file_name);
            $success['token_type'] = 'Bearer'; 
            
            
	        return response()->json($success, 200);
        } 
        else{ 
            
        	$error['message'] = 'Email or Password is Incorrected!';
        	return response()->json($error, 404);
           
        } 
    }

    public function logout(Request $request)
    {
        $user= User::where('email', $request->email)->first();
        $user->tokens()->delete();
        return response()->json(['message' => 'User successfully signed out']);
    }
}
