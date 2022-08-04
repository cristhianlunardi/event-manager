<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class AuthController extends ApiController
{
    public function logout(): JsonResponse
    {
        Auth::check();
        Auth::user()->token()->revoke();
        return $this->sendResponse([], "Successfully handled request (logout)");
    }
}
