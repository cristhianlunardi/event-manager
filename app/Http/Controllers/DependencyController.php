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
        $data = Dependency::all('name');

        return $this->sendResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDependencyRequest $request
     * @return JsonResponse
     */
    public function store(StoreDependencyRequest $request): JsonResponse
    {
        $newDependency = Dependency::create($request->validated());

        return $this->sendResponse($newDependency);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $dependency = Dependency::findOrFail($id);

        return $this->sendResponse($dependency);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDependencyRequest $request
     * @return JsonResponse
     */
    public function update(UpdateDependencyRequest $request, string $id): JsonResponse
    {
        //$dependency = Dependency::where('_id', $id)->firstOrFail();
        $dependency= Dependency::findOrFail($id);
        $dependency->fill($request->validated());
        $dependency->save();

        return $this->sendResponse($dependency);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function destroy(DestroyDependencyRequest $request, string $id): JsonResponse
    {
        Dependency::findOrFail($id)->delete();

        return $this->sendResponse();
    }
}
