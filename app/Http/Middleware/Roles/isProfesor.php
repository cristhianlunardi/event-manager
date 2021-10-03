<?php

namespace App\Http\Middleware\Roles;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isProfesor
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
        if (Auth::user() == null || Auth::user()->role == null)
        {
            return response()->json(['error' => 'The user have not privileges to perform this action.'], 403);
        }

        if (Role::findOrFail(Auth::user()->role)->name == 'Admin')
        {
            return $next($request);
        }
        else if (Role::findOrFail(Auth::user()->role)->name == 'Decano')
        {
            return $next($request);
        }
        else if (Role::findOrFail(Auth::user()->role)->name == 'Coordinador')
        {
            return $next($request);
        }
        else if (Role::findOrFail(Auth::user()->role)->name == 'Profesor')
        {
            return $next($request);
        }

        return response()->json(['error' => 'The user have not privileges to perform this action.'], 403);
    }
}
