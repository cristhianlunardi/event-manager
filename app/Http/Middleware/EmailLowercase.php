<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EmailLowercase
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
        if ($request['email'])
        {
            $request['email'] = mb_strtolower($request['email']);
        }

        return $next($request);
    }
}
