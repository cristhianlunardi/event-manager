<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dependency;

class DependencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dependencies = Dependency::orderBy('name', 'asc')->get();

        return response()->json( [
            'data' => $dependencies,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        $validated = $request->validate([
            'name' => [ 'required' ],
        ]);

        $dependency = new Dependency();
        $dependency->name = $request->name;
        $dependency->save();

        return response()->json( [
            'message' => 'Dependency created succesfully.',
            'data' => $dependency,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        $dependency = Dependency::where( '_id', $id )->get();

        return response()->json( [
            'data' => $dependency
        ], 200);
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
        $dependency = Dependency::find($id);

        if ($dependency == null)
        {
            return response()->json($this->handleErrors('notfound'), 404);
        }

        $validated = $request->validate([
            'name' => [ 'required' ],
        ]);

        $dependency->name = $request->name;
        $dependency->save();

        return response()->json( [
            'message' => 'Dependency updated succesfully.',
            'data' => $dependency,
        ], 200);
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
            'data' => [ 'required' ]
        ]);

        $data = $request->data;

        foreach ($data as $id) 
		{
            if (array_key_exists('_id', $id))
            {
                Dependency::where('_id', $id['_id'])->delete();
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
