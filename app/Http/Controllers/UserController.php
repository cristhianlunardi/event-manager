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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use MongoDB\BSON\Regex;
use stdClass;
use const App\DEFAULT_PAGE_SIZE;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register']]);
        $this->middleware('isValidUser', ['except' => ['register']]);

        $this->middleware('keyLowercase', ['only' => ['editRole']]);
        $this->middleware('emailLowercase', ['only' => ['register', 'createUser', 'update', 'updateUser']]);
        //$this->middleware('isCoordinator', ['only' => ['getUsers', 'updateUser', 'editRole', 'destroyUser', 'createUser']]);

        //$this->middleware('checkPermission:create_user_with_role', ['only' => ['createUserWithRole']]);
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
        $user = Auth::user();
        $hasPermission = $user->hasPermission('create_user');

        if (!$hasPermission)
        {
            return $this->sendForbiddenResponse(errors: array('create_user' => 'False'));
        }

        $newUser = User::create($request->validated());
        $newUser->isActive = true;

        $this->prepareUserToSave($newUser);
        $newUser->save();

        return $this->sendResponse($newUser->toArray());
    }

    public function createUserWithRole(CreateUserWithRoleRequest $request): JsonResponse
    {
        echo $request->role;
        echo Role::where('_id', Auth::user()->role)->first()->name;
        echo "hello world";

        //return $this->sendResponse();
    }

    public function getUsers(Request $request): JsonResponse
    {
        $user = Auth::user();
        $hasPermission = $user->hasPermission('view_user');

        if (!$hasPermission)
        {
            return $this->sendForbiddenResponse(errors: array('view_user' => 'False'));
        }

        $pageSize = (int)$request->query('page_size', DEFAULT_PAGE_SIZE);
        $result = $this->queryAllActiveUsers();
        $search_param = $request->query('search');

        if ($search_param)
        {
            $result = User::whereNotNull('email')->where('isActive', true)->where(function ($query) use ($search_param) {
                print("nani kore");
                return $query->where('email', 'like', $search_param.'%')->orWhere('fullName', 'like', $search_param.'%');
            });
        }

        $result = $result->orderBy('fullName', 'asc')->paginate($pageSize);

        return $this->sendResponse($result);
    }

    public function getUser($targetEmail): JsonResponse
    {
        $user = Auth::user();
        $hasPermission = $user->hasPermission('view_user');

        if (!$hasPermission)
        {
            return $this->sendForbiddenResponse(errors: array('view_user' => 'False'));
        }

        $target_user = $this->findUserByEmail($targetEmail);

        if (!$target_user->success)
        {
            return $this->sendError(404, 'There is no User registered with that email ('.$targetEmail.').', ['email' => 'No user found with the given email.']);
        }

        return $this->sendResponse($target_user);
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
        $user = Auth::user();
        $hasPermission = $user->hasPermission('delete_user');

        if (!$hasPermission)
        {
            return $this->sendForbiddenResponse(errors: array('delete_user' => 'False'));
        }

        $content = $this->findUserByEmail($targetEmail);

        if (!$content->success)
        {
            return $this->sendError(404, 'There is no User registered with that email ('.$targetEmail.').', ['email' => 'No user found with the given email.']);
        }

        $user = $content->user;
        $tokens = $user->tokens;
        foreach ($tokens as $token)
        {
            $token->revoke();
        }
        $user->isActive = false;
        $user->save();

        return $this->sendResponse($tokens);
    }

    public function selfUser(): JsonResponse
    {
        $user = Auth::user();

        if ($user == null) return $this->sendError(404);

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
        $user = Auth::user();
        $hasPermission = $user->hasPermission('update_user');

        if (!$hasPermission)
        {
            return $this->sendForbiddenResponse(errors: array('update_user' => 'False'));
        }

        $content = $this->findUserByEmail($targetEmail);

        if (!$content->success)
        {
            return $this->sendError(404, 'There is no User registered with that email ('.$request->email.').', ['email' => 'No user found with the given email.']);
        }

        $user = $content->user;

        $user->fill($request->validated());

        if ($request->dependency) {
            $user->dependency = Dependency::getDependenciesFromNames($user->dependency)->toArray();
        }

        if ($request->role) {
            $user->role = Role::getIdFromName($user->role)->toArray();
        }

        $user->save();

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
        $user->dependencies = Dependency::whereIn('_id', [$user->dependencies])->get();

        return $user;
    }

    private function prepareUserToSave(User $user): User {
        $user->role = Role::getIdFromName($user->role)->toArray();
        $user->dependency = Dependency::getDependenciesFromNames($user->dependency)->toArray();

        return $user;
    }

    private function findUserByEmail($email)
    {
        $result = new stdClass();

        $user = User::where('email', strtolower($email))->where('isActive', true)->first();
        if (empty($user))
        {
            $result->success = false;
            return $result;
        }

        $result->user = $user;
        $result->success = true;

        return $result;
    }

    private function queryAllActiveUsers()
    {
        $users = User::whereNotNull('email')->where('isActive', true);

        return $users;
    }
}
