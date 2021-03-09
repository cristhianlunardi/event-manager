<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', []);
    }

    public function getUsers()
    {
        $data = User::all();

        //$users = DB::collection("users")

        return $this->sendResponse($data, "Successfully handled request");
    }
}
