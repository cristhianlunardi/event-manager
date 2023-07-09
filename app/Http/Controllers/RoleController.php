<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Models\Dependency;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RoleController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->middleware('isValidUser', ['except' => ['index', 'show']]);
        $this->middleware('keyLowercase', ['only' => ['store', 'update']]);

        // Privileges
        $this->middleware('isProfessor', ['only' => ['store']]);
        $this->middleware('isCoordinator', ['only' => ['update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $result = Role::whereNotNull('name')->orderBy('name', 'asc')->get();

        return $this->sendResponse($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRoleRequest  $request
     * @return JsonResponse
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $newRole = Role::create($request->validated())->name;

        return $this->sendResponse(['name' => $newRole]);
    }

    /**
     * Display the specified resource.
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        // There is no reason to request roles with its names
        // Neither using role id (since front-end hav not access to them)

        //$role = Role::findOrFail($id)->name;

        //return $this->sendResponse(['name' => $role]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRoleRequest $request
     * @param string $name
     * @return JsonResponse
     */
    public function update(UpdateRoleRequest $request, string $name): JsonResponse
    {
        $role = Role::where('name', $name)->first();

        if (empty($role))
        {
            return $this->sendError(404, 'The Role called '.$name.' was not found.');
        }

        $role->update($request->validated());

        return $this->sendResponse($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $name
     * @return JsonResponse
     */
    public function destroy(string $name): JsonResponse
    {
        $role = Role::where('name', $name)->delete();

        if (!$role)
        {
            return $this->sendError(404, 'The Role called '.$name.' was not found.');
        }

        return $this->sendResponse();
    }

    /**
     * Return all the permissions for the current User.
     *
     * @return JsonResponse
     */
    public function getMyPermissions(): JsonResponse
    {
        $currentUser = Auth::user();
        $permissions = Role::getRolesPermissions($currentUser);
        $userDependencies = $currentUser->dependency;
        $userRoles = $currentUser->role;

        return $this->sendResponse([
            'permissions' => $permissions,
            'dependency' => $userDependencies,
            'role' => $userRoles,
        ]);
    }
}
