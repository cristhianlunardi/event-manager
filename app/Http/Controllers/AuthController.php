<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends ApiController
{
    public $token = true;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register', 'login']]);
    }

    public function testOauth()
    {
        $user = Auth::user();

        return response()->json([
            'message' => 'Success',
            'data' => [
                'user' => $user
            ]
        ], 200);
    }

    public function register(Request $request)
    {
        if ($request['email'] != null)
        {
            $request['email'] = strtolower($request['email']);
        }

        $request->validate([
            'name' => 'required',
            'email' => 'bail | required | email | unique:users',
            'password' => 'required | min:8',
            'c_password' => 'required | same:password',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        $token = $user->createToken("EventManager")->accessToken;

        return response()->json([
            'message' => 'User created succesfully.',
            'data' => [
                'token' => $token,
                'user' => $user
            ]
        ], 200);
    }

    public function login(Request $request)
    {
        $request['email'] = strtolower($request['email']);

        $validator = Validator::make($request->all(), 
        [ 
            'email' => 'bail|required|email',
            'password' => 'required',  
        ]);

        if ($validator->fails())
        {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function getUser(Request $request)
    {
        $user = Auth::user();

        $data = [
            'user' => $user
        ];

        return $this->sendResponse($data, "Successfully handled request");
    }

    public function delete(Request $request)
    {
        $validated = $request->validate([
            'data' => 'required'
        ]);

        $data = $request->data;

        foreach ($data as $id) 
		{
            if (array_key_exists('_id', $id))
            {
                User::where('_id', $id['_id'])->delete();
            }
        }

        return response()->json(['message' => 'Users deleted succesfully.'], 200);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->token()->revoke();
            return $this->sendResponse([], "Successfully handled request");
        }else{
            return $this->sendResponse([ "The user is not logged in" ], "Not logged in", 500);
        }
    }
}
