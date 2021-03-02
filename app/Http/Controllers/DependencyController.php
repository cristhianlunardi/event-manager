<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dependency;

class DependencyController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$dependencies = Dependency::orderBy('name', 'asc')->get();
        $data = Dependency::all();

        return $this->sendResponse($data, "Successfully handled request");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // ! Bug 001 : - Need to verify if every "dependency key" is unique (inside the request)
        $validated = $request->validate([
            'data.*.key' => 'required | unique:dependencies',
            'data.*.name' => 'required'
        ]);

        $result = [];

        foreach ($request->data as $dependency)
        {
            $dependency['key'] = strtolower($dependency['key']);
            $newDependency = Dependency::create($dependency);
            array_push($result, $newDependency->toArray());
        }

        return $this->sendResponse($result, "Dependecies created successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dependency = Dependency::where('_id', $id)->get();

        return $this->sendResponse([$dependency], "Successfully handled request");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'data.*.name' => 'required'
        ]);

        $data = $request->data;

        foreach ($data as $dependencyUpdated) 
		{
            if (array_key_exists('_id', $dependencyUpdated))
            {
                $dependency = Dependency::find($dependencyUpdated['_id']);

                if ($dependency)
                {
                    $dependency->fill($dependencyUpdated);
                    $dependency->update();
                }
            }
        }

        return response()->json(['message' => 'Users updated succesfully.'], 200);
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
