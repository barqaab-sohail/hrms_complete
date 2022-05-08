<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

      //this function override
    public function showLinkRequestForm()
    {
        $url = \Request::url();
        $lastWord = substr($url, strrpos($url, "/") + 1);
        if($lastWord == "pmsReset"){
             return view('auth.pms.passwords.email');
         }else{
            return view('auth.passwords.email');
         }
       
    }

    
}
