<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
class ApiMiddleware
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
        echo User::all()[0]->name."\r\n";
        // exit();
        return $next($request);
    }
}
