<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserWithRoleRequest;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\EditUserRoleRequest;
use App\Http\Requests\User\DeleteUserRequest;
use App\Http\Requests\User\RegisterUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Dependency;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use stdClass;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register']]);
        $this->middleware('isValidUser', ['except' => ['register']]);

        $this->middleware('keyLowercase', ['only' => ['editRole']]);
        $this->middleware('emailLowercase', ['only' => ['register', 'createUser', 'update', 'updateUser']]);
        $this->middleware('isCoordinator', ['only' => ['getUsers', 'updateUser', 'editRole', 'destroyUser', 'createUser']]);

        $this->middleware('checkPermission:create_user_with_role', ['only' => ['createUserWithRole']]);
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $newUser = User::create(['email' => mb_strtolower($request->email)]);
        $newUser->fill($request->validated());
        $newUser->isActive = true;
        $this->prepareUserToSave($newUser);
        $token = $newUser->createToken("EventManager")->accessToken;
        $newUser->save();

        $this->prepareUserResponse($newUser);

        return $this->sendResponse(array_merge(["token"=>$token], $newUser->toArray()));
    }

    public function createUser(CreateUserRequest $request): JsonResponse
    {
        $newUser = User::create(['email' => mb_strtolower($request->email)]);
        $newUser->fill($request->validated());
        $newUser->isActive = true;
        $this->prepareUserToSave($newUser);
        $newUser->save();

        $this->prepareUserResponse($newUser);

        return $this->sendResponse($newUser->toArray());
    }

    public function createUserWithRole(CreateUserWithRoleRequest $request): JsonResponse
    {
        echo $request->role;
        echo Role::where('_id', Auth::user()->role)->first()->name;
        echo "hello world";

        //return $this->sendResponse();
    }

    public function getUsers(): JsonResponse
    {
        $data = User::whereNotNull('email')->orderBy('fullName', 'asc')->get();

        foreach ($data as $user)
        {
            $this->prepareUserResponse($user);
        }

        return $this->sendResponse($data);
    }

    /**
     * Verify if the token is available or expired (401 for expired)
     *
     * @return JsonResponse
     */
    public function verifyToken(): JsonResponse
    {
        return $this->sendResponse();
    }

    public function destroyUser(DeleteUserRequest $request, $targetEmail): JsonResponse
    {
        $content = $this->findUserByEmail($targetEmail);

        if (!$content->success)
        {
            return $this->sendError(404, 'There is no User registered with that email ('.$request->email.').', ['email' => 'No user found with the given email.']);
        }

        if (Hash::check($request->password, Auth::user()->password) == false)
        {
            return $this->sendError(403, "The password is incorrect.");
        }

        $user = $content->user;
        $user->isActive = false;
        $user->save();

        return $this->sendResponse();
    }

    public function selfUser(): JsonResponse
    {
        $user = Auth::user();
        $user = User::find($user['_id']);

        // Should not generates error since there's a middleware verifying a user is logged in
        if ($user == null) return $this->sendError(404);

        $user = $this->prepareUserResponse($user);

        return $this->sendResponse($user);
    }

    public function destroy(DeleteUserRequest $request): JsonResponse
    {
        // selfDestroy
        $user = User::where('email', Auth::user()->email)->first();

        if ($user == null) return $this->sendError(404);
        if (Hash::check($request->password, $user->password) == false)
        {
            return $this->sendError(403, "The password is incorrect.");
        }

        $user->isActive = false;
        $user->save();

        return $this->sendResponse();
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        return $this->updateUser($request, Auth::user()->email);
    }

    public function updateUser(UpdateUserRequest $request, $targetEmail): JsonResponse
    {
        $content = $this->findUserByEmail($targetEmail);

        if (!$content->success)
        {
            return $this->sendError(404, 'There is no User registered with that email ('.$request->email.').', ['email' => 'No user found with the given email.']);
        }

        $user = $content->user;

        $user->fill($request->validated());
        if ($request->dependency)
        {
            $user->dependency = Dependency::where('name', $request->dependency)->first()->id;;
        }

        $user->save();
        $this->prepareUserResponse($user);

        return $this->sendResponse($user);
    }

    public function editRole(EditUserRoleRequest $request, $targetEmail): JsonResponse
    {
        $content = $this->findUserByEmail($targetEmail);

        if (!$content->success)
        {
            return $this->sendError(404, 'There is no User registered with that email ('.$targetEmail.').', ['email' => 'No user found with the given email.']);
        }

        $user = $content->user;

        $user->role = Role::getIdFromName($request->name);
        $user->save();

        $this->prepareUserResponse($user);

        return $this->sendResponse($user);
    }

    private function prepareUserResponse(User $user): User
    {
        $user->role = Role::getNameFromId($user->role);
        //$user->dependency = Dependency::getNameFromId($user->dependency);
        $user->dependencies = Dependency::whereIn('_id', $user->dependencies)->get();

        return $user;
    }

    private function prepareUserToSave(User $user): User {
        $user->role = Role::getIdFromName($user->role);
        $user->dependency = Dependency::getIdFromName($user->dependency);

        return $user;
    }

    private function findUserByEmail($email)
    {
        $result = new stdClass();

        $user = User::where('email', $email)->first();
        if (empty($user))
        {
            $result->success = false;
            return $result;
        }

        $result->user = $user;
        $result->success = true;

        return $result;
    }
}
