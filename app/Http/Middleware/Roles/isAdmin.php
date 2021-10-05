<?php

namespace App\Http\Middleware\Roles;

use App\Models\Role;
use App\Privileges;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isAdmin
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
        $userRole = Role::where('_id', Auth::user()->role)->first();

        if (empty($userRole))
        {
            return response()->json(['error' => 'The user have not privileges to perform this action.'], 403);
        }

        if (in_array($userRole->name, Privileges::ADMIN_HIERARCHY))
        {
            return $next($request);
        }

        return response()->json(['error' => 'The user have not privileges to perform this action.'], 403);
    }
}
