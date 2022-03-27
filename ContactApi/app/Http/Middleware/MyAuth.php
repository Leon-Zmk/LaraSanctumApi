<?php

namespace App\Http\Middleware;

use Closure;

class MyAuth
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

        $key=["normal","vip"];

        if(!$request->has("key") || !in_array($request->key,$key)){
            return response(["message"=>"Action Unauthorized Please Enter Your Key","Status"=>"403"],403);
        }
        return $next($request);
    }
}
