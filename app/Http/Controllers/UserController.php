<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\DeleteUserRequest;
use App\Http\Requests\User\RegisterUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Dependency;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
{
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $newUser = new User();
        $newUser->fill($request->validated());
        $newUser->isValid = true;
        $newUser->save();
        $token = $newUser->createToken("EventManager")->accessToken;

        return $this->sendResponse(array_merge(["token"=>$token], $newUser->toArray()));
    }

    public function getUsers(): JsonResponse
    {
        $data = User::all();

        return $this->sendResponse($data);
    }

    public function destroyUser(DeleteUserRequest $request): JsonResponse
    {
        $user = User::where('email', Auth::user()->email)->first();

        if ($user == null) return $this->sendError(404);
        if (Hash::check($request->password, $user->password) == false)
        {
            return $this->sendError(403, "The given credentials doesn't match.");
        }

        $user->isValid = false;
        $user->save();

        return $this->sendResponse();
    }

    public function selfUser(): JsonResponse
    {
        $user = Auth::user();

        $role = Role::find($user['role']);
        $user['role'] = $role['name'];

        $dependency = Dependency::find($user['dependency']);
        $user['dependency'] = $dependency['name'];

        return $this->sendResponse($user);
    }

    public function updateUser(UpdateUserRequest $request): JsonResponse
    {
        $user = User::where('email', Auth::user()->email)->first();
        $user->fill($request->validated());
        $user->save();

        return $this->sendResponse($user);
    }
}
