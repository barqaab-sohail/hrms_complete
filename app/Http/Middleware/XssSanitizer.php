<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class XssSanitizer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        
        $input = $request->all();
        
        array_walk_recursive($input, function(&$input) {
            
            if($input !=''){  //It is mandatory to use otherwise it also effect null date value
                $input = strip_tags($input);
            }
        });
        
        $request->merge($input);


        return $next($request);
    }
}
