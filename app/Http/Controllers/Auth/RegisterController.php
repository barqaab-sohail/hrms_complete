<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\StoreOTP;
use App\User;
use App\employee;
use App\Notifications\MailNotification;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use App\Providers\RouteServiceProvider;





class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    //this function override
    public function showRegistrationForm()
    {
        $url = \Request::url();
        $lastWord = substr($url, strrpos($url, "/") + 1);
        if($lastWord == "pmsRegister"){
             return view('auth.pms.register');
         }else{
            return view('auth.register');
         }
       
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', ],
            //'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(Request $request)
    {
        
        $email = $request->email;

        return view('auth.codeConfirmation',compact('email'));
    }

    public function store(StoreOTP $request){

        $user = user::all()->where('email',$request->email)->first();
       
        if ($user->code == $request->otp){
            DB::table('users')
                ->where('email', $request->email)
                ->update(['user_status' => 1, 'email_verified_at' =>  \Carbon\Carbon::now(),'password' => Hash::make($request->password) ]); 

        return redirect()->route('login')->with('success', 'You are Successfully registered')->with(['email'=>$request->email]);

        }else{

            
            return back()->with( ['email'=>$request->email])->withErrors("code is not correct");
        }
    }

    public function register(Request $request)
    {
       
       $this->validator($request->all())->validate();
           
         $test = DB::table('users')
                    ->where('email', $request->email)->first();
         
        if ($test == null)
        {
            return view('auth.register')->withErrors("This Email Address is not Found. Please Contact to HR");

        }
        else
        {

            if($test->user_status==1)
            {
                 return view('auth.login')->withErrors("You are already Registered.  Please Enter Email and Password");
            }elseif($test->user_status==0)
            {
                $otpcode = rand (10000,65000);

            DB::table('users')
                ->where('email', $request->email)
                ->update(['code' => $otpcode]);

            $email = $request->email;

            $user = User::where('email', $request->email)->first();
            $user->notify(New MailNotification($otpcode));

            $message = 'Verification code is send to your email address.  Please enter below for varification';

            return view('auth.codeConfirmation',compact('email','message'));//Redirect::route('otp.create')->with( ['message' => $message, 'email'=>$email] );
            
            }else{
                return view('auth.register')->withErrors("Please Contact to HR");
            }
        }
       
    }
}
