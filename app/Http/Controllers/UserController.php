<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getUsers()
    {
        $data = User::all();

        return response()->json( [
            'message' => 'Success.',
            'data' => $data,
        ], 200);
    }
}
