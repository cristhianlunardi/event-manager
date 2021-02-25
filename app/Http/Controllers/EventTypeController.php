<?php

namespace App\Http\Controllers;

use App\Models\EventType;
use Illuminate\Http\Request;
use App\Http\Resources\EventTypeResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EventTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eventTypes = EventType::all();

        return response()->json( [
            'data' => $eventTypes,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'fields' => 'required',
        ]);

        $eventType = new EventType();
        $eventType->name = $request->name;
        $eventType->fields = $request->fields;
        $eventType->save();

        return new EventTypeResource($eventType);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EventType  $eventType
     * @return \Illuminate\Http\Response
     */
    public function show(EventType $eventType, $id)
    {
        $eventType = EventType::find($id);
        if ($eventType == null)
        {
            return response()->json($this->handleErrors('notfound'), 404);
        }

        return new EventTypeResource($eventType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EventType  $eventType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $eventType = EventType::find($id);

        if ($eventType == null)
        {
            return response()->json($this->handleErrors('notfound'), 404);
        }

        $validated = $request->validate([
            'data' => 'required',
            'data.name' => 'required',
            'data.fields' => 'required',
        ]);

        $data = $request->data;

        $eventType->name = $data['name'];
        $eventType->fields = $data['fields'];

        $eventType->save();

        return response()->json( [
            'message' => 'EventType updated succesfully.',
            'data' => $eventType,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EventType  $eventType
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'data' => 'required'
        ]);

        $data = $request->data;

        foreach ($data as $id) 
		{
            if (array_key_exists('_id', $id))
            {
                EventType::where('_id', $id['_id'])->delete();
            }
        }

        return response()->json(['message' => 'EventTypes deleted succesfully.'], 200);
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
                            'id' => 'There isn\'t a EventType associated with that id.',
                        ]
                ];

                break;
            }
        }
    }
}
