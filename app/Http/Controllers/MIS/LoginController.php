<?php

namespace App\Http\Controllers\MIS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Hr\HrDocumentation;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:191',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validator_errors' => $validator->messages(),

            ]);
        } else {
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid Credentials',
                ]);
            } else {
                $token = $user->createToken($user->email . '_token')->plainTextToken;
                $picture = HrDocumentation::where('hr_employee_id', $user->hrEmployee->id)->where('description', 'picture')->first();
                return response()->json([
                    'status' => 200,
                    'userName' => $user->hrEmployee->full_name,
                    'userDesignation' => $user->hrEmployee->designation,
                    'pictureUrl' => asset('/storage/' . $picture->path . $picture->file_name),
                    'token' => $token,
                    'message' => 'Loogged In Successfully',
                ]);
            }
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Sucessfully Logout'
        ]);
    }
}
