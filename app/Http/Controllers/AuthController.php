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
        // ! Bug 001 : - Need to verify if every email is unique (inside the request)
        $validated = $request->validate([
            'data' => 'required',
            'data.*.email' => 'unique:users'
        ]);

        // email must be "LOWERCASE" - Front-end duty
        $result = [];

        foreach ($request->data as $user)
        {
            $newUser = User::create($user);
            $token = $newUser->createToken("EventManager")->accessToken;
            array_push($result, array_merge([ "token" => $token ], $newUser->toArray()));
        }

        return $this->sendResponse($result, "Successfully handled request");
    }

    public function deleteUsers(Request $request)
    {
        $validated = $request->validate([
            'data' => 'required'
        ]);

        $data = $request->data;

        foreach ($data as $user)
		{
            if (array_key_exists('email', $user))
            {
                User::where('email', $user['email'])->delete();
            }
        }

        return response()->json(['message' => 'Users deleted succesfully.'], 200);
    }

    public function logout()
    {
        Auth::check();
        Auth::user()->token()->revoke();
        return $this->sendResponse([], "Successfully handled request (logout)");
    }

    public function selfUser()
    {
        $user = Auth::user();

        $data = [
            'user' => $user
        ];

        return $this->sendResponse($data, "Successfully handled request");
    }

    public function update(RegisterUser $request)
    {
        $data = $request->data;
        $result = [];

        foreach ($data as $userUpdated)
		{
            if (array_key_exists('_id', $userUpdated))
            {
                $user = User::find($userUpdated['_id']);

                if ($user)
                {
                    $user->fill($userUpdated);
                    $user->update();
                    array_push($result, $user->toArray());
                }
            }
        }

        return $this->sendResponse($result, "Users updated succesfully");
        //return response()->json(['message' => 'Users updated succesfully.'], 200);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function handleErrors($error)
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
