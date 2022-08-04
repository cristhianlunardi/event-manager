<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsValidUser
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
        if (Auth::user() == null || Auth::user()->isActive == false || Auth::user()->role == null)
        {
            return response()->json(['error' => 'The user is disabled or do not exist.'], 403);
        }

        return $next($request);
    }
}
