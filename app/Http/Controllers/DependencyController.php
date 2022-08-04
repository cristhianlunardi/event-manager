<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDependencyRequest;
use Illuminate\Http\Request;
use App\Models\Dependency;
use Illuminate\Http\JsonResponse;

class DependencyController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
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
        // BUG001 : - Need to verify if every "dependency key" is unique (inside the request)
        $result = [];

        foreach ($request->data as $dependency)
        {
            $dependency['key'] = strtolower($dependency['key']);
            $newDependency = Dependency::create($dependency);
            array_push($result, $newDependency->toArray());
        }

        return $this->sendResponse($result);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $dependency = Dependency::where('_id', $id)->first();

        return $this->sendResponse($dependency);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'data' => 'required',
            'data.*_id' => 'required',
            'data.*.key' => 'required',
            'data.*.name' => 'required',
        ]);

        $data = $request->data;
        $result = [];

        foreach ($data as $dependencyUpdated)
		{
            $dependency = Dependency::first($dependencyUpdated['_id']);

            if ($dependency)
            {
                $dependency->fill($dependencyUpdated);
                $dependency->update();
                array_push($result, $dependency->toArray());
            }
        }

        return $this->sendResponse($result, "Dependencies updated succesfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'data' => 'required'
        ]);

        $data = $request->data;

        foreach ($data as $dependency)
		{
            if (array_key_exists('_id', $dependency))
            {
                Dependency::where('_id', $dependency['_id'])->delete();
            }
        }

        return response()->json(['message' => 'Dependencies deleted succesfully.'], 200);
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
                            'id' => 'There isn\'t a Dependency associated with that id.',
                        ]
                ];

                break;
            }
        }
    }
}
