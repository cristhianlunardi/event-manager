<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dependency\DestroyDependencyRequest;
use App\Http\Requests\Dependency\UpdateDependencyRequest;
use App\Http\Requests\Dependency\StoreDependencyRequest;
use App\Models\Dependency;
use Illuminate\Http\JsonResponse;

class DependencyController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->middleware('validUser', ['except' => ['index', 'show']]);
        $this->middleware('keyLowercase', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $result = Dependency::whereNotNull('name')->orderBy('name', 'asc')->get(['name']);

        return $this->sendResponse($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDependencyRequest $request
     * @return JsonResponse
     */
    public function store(StoreDependencyRequest $request): JsonResponse
    {
        $newDependency = Dependency::create($request->validated())->name;

        return $this->sendResponse(['name' => $newDependency]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $dependency = Dependency::findOrFail($id)->name;

        return $this->sendResponse(['name' => $dependency]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDependencyRequest $request
     * @return JsonResponse
     */
    public function update(UpdateDependencyRequest $request, string $name): JsonResponse
    {
        /*$dependency = Dependency::where('name', $name);
        $dependency->update($request->validated(), ['upsert' => true])->get();
        //$dependency->fill($request->validated());
        //$dependency->save();

        return $this->sendResponse(['name' => $dependency['name']]);*/
        return $this->sendError(404, 'Not available');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function destroy(string $name): JsonResponse
    {
        $dependency = Dependency::where('name', $name)->delete();

        if (!$dependency)
        {
            return $this->sendError(404, 'The Dependency called '.$name.' was not found.');
        }

        return $this->sendResponse();
    }
}
