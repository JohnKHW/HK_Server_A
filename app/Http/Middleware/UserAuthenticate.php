<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserAuthenticate
{
    public function handle(Request $request, Closure $next, $role)
    {
        
        error_log($request);
        error_log($role);

        return $next($request);
    }
}
