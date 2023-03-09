<?php

namespace App\Http\Middleware;

use Closure;
use App\Token;
class TokenMiddleware
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
        //dd($request->token);
        $token = Token::where('token',$request->token)->first();
        if($token == null)
        {
          return response()->json(["status"=>false,"message"=>"Invalid token"], 404);
        }
        else
        {
            // $request->merge(array("name" => "arajit mondal"));
           return $next($request); 
        }
        
    }
}
