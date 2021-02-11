<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
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

    /*public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt($input))
        {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'success' => true,
            'token' => $jwt_token,
        ]);
    }*/

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

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try
        {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        }
        catch (JWTException $exception)
        {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUser(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        return response()->json(['user' => $user]);
    }

    public function all()
    {
        /*$dependencies = User::orderBy('name', 'asc')->get();

        return response()->json( [
            'data' => $dependencies,
        ], 200);*/
        return true;
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
