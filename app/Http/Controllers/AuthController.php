<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterUser;
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

    public function register(RegisterUser $request)
    {
        echo $request;
        /*if ($request['email'] != null)
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
        ], 200);*/
    }

    public function getUser(Request $request)
    {
        $user = Auth::user();

        $data = [
            'user' => $user
        ];

        return $this->sendResponse($data, "Successfully handled request");
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if ($user == null)
        {
            return response()->json($this->handleErrors('notfound'), 404);
        }

        $validated = $request->validate([
            'data' => 'required',
            'data._id' => 'required',
            'data.name' => 'required',
            'data.email' => 'required',
        ]);

        $data = $request->data;

        $user->name = $data['name'];
        $user->_id = $data['_id'];
        $user->email = $data['email'];

        $user->save();

        return response()->json( [
            'message' => 'EventType updated succesfully.',
            'data' => $user,
        ], 200);
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

    public function handleErrors( $error )
    {
        switch ( $error )
        {
            case 'notfound':
            {
                return [
                    'message' => 'The given data was invalid.',
                    'errors' =>
                        [
                            'id' => 'There isn\'t a User associated with that id.',
                        ]
                ];

                break;
            }
        }
    }
}
