<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DependencyKeyLowercase
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
        $request['key'] = strtolower($request['key']);
        return $next($request);
    }
}
