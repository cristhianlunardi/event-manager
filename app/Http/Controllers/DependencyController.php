<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dependency\UpdateDependencyRequest;
use App\Http\Requests\Dependency\StoreDependencyRequest;
use App\Models\Dependency;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MongoDB\BSON\Regex;
use const App\DEFAULT_PAGE_SIZE;

class DependencyController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->middleware('isValidUser', ['except' => ['index', 'show']]);
        $this->middleware('keyLowercase', ['only' => ['store', 'update']]);

        // Privileges
        $this->middleware('isSecretary', ['only' => ['store']]);
        $this->middleware('isProfessor', ['only' => ['update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $pageSize = (int)$request->query('page_size', DEFAULT_PAGE_SIZE);
        $result = Dependency::whereNotNull('name');

        $queryName = $request->query('name');

        if ($queryName) {
            $myReg = new Regex('(?:.+)?'.$queryName.'(?:.+)?', 'mig');
            $result = $result->where('name', 'regexp', $myReg);
        }

        $result = $result->select(['name'])->orderBy('name', 'asc')->paginate($pageSize);

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
        $newDependency = Dependency::create($request->validated());

        return $this->sendResponse([$newDependency]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $queryParam
     * @param Request $request
     * @return JsonResponse
     */
    public function show(string $queryParam, Request $request): JsonResponse
    {
        $pageSize = (int)$request->query('page_size', DEFAULT_PAGE_SIZE);
        $myReg = new Regex('(?:.+)?'.$queryParam);
        $result = Dependency::whereNotNull('name')->where('name', 'regexp', $myReg)->select(['name'])->orderBy('name', 'asc')->paginate($pageSize);

        return $this->sendResponse($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDependencyRequest $request
     * @param string $name
     * @return JsonResponse
     */
    public function update(UpdateDependencyRequest $request, string $name): JsonResponse
    {
        $dependency = Dependency::where('name', $name)->first();

        if (empty($dependency))
        {
            return $this->sendError(404, 'The Dependency called '.$name.' was not found.');
        }

        $dependency->update($request->all());

        return $this->sendResponse(['name' => $dependency->name]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $name
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
