<?php

namespace App\Http\Middleware;
use DB;
use Auth;

use Closure;

class SessionTimeOut
{
   
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        //If User not perform 60 minutes than logout
        // if (auth()->user()){
           
        //     $lastActivityTime =  DB::table('sessions')->where('user_id', auth()->user()->id)->pluck('last_activity')->first();
           
        //     if ((time()-$lastActivityTime) >= (config('session.lifetime'))){
              
        //         Auth::logout();
        //     }
        // }

       return $next($request);
    }
}
