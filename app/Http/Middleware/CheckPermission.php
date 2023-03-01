<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param String $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, String $permission)
    {
        echo Auth::user()->role;
        echo "\n";
        $role = Role::where('_id', Auth::user()->role)->first();
        echo "\n";

        echo $role;

        if ($role !== null)
        {
            if (in_array($permission, $role->permissions))
            {
                return $next($request);
            }
        }

        $response = [
            'success' => false,
            'message' => "Forbidden",
        ];

        return response()->json($response, 403);
    }
}
