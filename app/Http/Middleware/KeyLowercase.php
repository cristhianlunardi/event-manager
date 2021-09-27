<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KeyLowercase
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
        $request['key'] = mb_strtolower($request['name']);
        return $next($request);
    }
}
