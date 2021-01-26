<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
/*use JWTAuth;
use Validator;
use AppUser;
use Illuminate/Http/Request;
use App/Http/Requests/RegisterAuthRequest;
use TymonJWTAuthExceptionsJWTException;
use SymfonyComponentHttpFoundationResponse;*/
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class JwtAuthController extends Controller
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

    public function register(Request $request)
    {
        $request['email'] = strtolower($request['email']);

        $validator = Validator::make($request->all(), 
        [ 
            'name' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'required',  
            'c_password' => 'required|same:password', 
        ]);  

        if ($validator->fails())
        {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        /*if ($this->token)
        {
            return $this->login($request);
        }*/

        return response()->json([
            'success' => true,
            'data' => $user
        ], Response::HTTP_OK);
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

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
