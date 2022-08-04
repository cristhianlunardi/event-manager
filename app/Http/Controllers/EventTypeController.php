<?php

namespace App\Http\Controllers;

use App\Models\EventType;
use Illuminate\Http\Request;
use App\Http\Resources\EventTypeResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EventTypeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

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
            'data' => 'required',
            'data.*.name' => 'required',
            'data.*.key' => 'required|unique:event_types',
            'data.*.fields' => 'required',
        ]);

        $result = [];

        foreach ($request->data as $eventType)
        {
            $eventType['key'] = strtolower($eventType['key']);
            $newEventType = EventType::create($eventType);
            array_push($result, $newEventType->toArray());
        }

        return $this->sendResponse($result, "Event types created successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EventType  $eventType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dependency = EventType::where('_id', $id)->get();

        return $this->sendResponse($dependency, "Successfully handled request");
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
        $validated = $request->validate([
            'data' => 'required',
            'data.*._id' => 'required',
            'data.*.name' => 'required',
            'data.*.key' => 'required',
            'data.*.fields' => 'required',
        ]);

        $data = $request->data;
        $result = [];

        foreach($data as $eventTypeUpdated)
        {
            try
            {
                $eventType = $this->findIdOrFail($eventTypeUpdated['_id']);
            }
            catch (ModelNotFoundException $e)
            {
                //return $this->handleErrors('_id');
                $k = $e->getMessage();
                return $k;
            }

            /*if ($eventType->key != $eventTypeUpdated['key'])
            {
                try
                {
                    $errors = $this->uniqueKeyOrFail($eventTypeUpdate['key']);
                }
                if ($errors != null) return;
            }*/
        }

        /*foreach ($data as $eventTypeUpdated) 
		{
            $eventType = EventType::find($eventTypeUpdated['_id']);
            $eventType->fill($eventTypeUpdated);
            $eventType->update();
            array_push($result, $eventType->toArray());
        }

        return $this->sendResponse($result, "Event types updated succesfully");*/
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
            'data' => 'required',
            'data.*._id' => 'required',
        ]);

        $data = $request->data;

        foreach($data as $deleteElement)
        {
            $eventType = $this->findIdOrFail($deleteElement['_id']);
        }

        foreach ($data as $deleteElement) 
		{
            Dependency::where('_id', $deleteElement['_id'])->delete();
        }

        return response()->json(['message' => 'Dependencies deleted succesfully.'], 200);
    }

    private function findIdOrFail($id)
    {
        $eventType = EventType::find($id);
        if (!$eventType)
        {
            throw new ModelNotFoundException($this->handleErrors('_id'));
        }

        return $eventType;
    }

    private function uniqueKeyOrFail($key)
    {
        $test = EventType::where('key', $key)->get();
        if (count($test) > 0)
        {
            throw new ID();
        }

        return $test;
    }

    private function handleErrors(string $error)
    {
        switch ($error)
        {
            case '_id':
            {
                $errors = [
                    '_id' => "There is not EventType using the given _id",
                ];

                return $this->sendError("Wrong data values", $errors, 422);
            }
            
            case 'key':
            {
                $errors = [
                    'key' => "There exist one 'key' property with the same name in the database ('{$eventTypeUpdated['key']}')",
                ];
            
                return $this->sendError("Wrong data values", $errors, 422); 
            }
        }
    }
}
