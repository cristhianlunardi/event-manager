<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventType\StoreEventTypeRequest;
use App\Http\Requests\EventType\UpdateEventTypeRequest;
use App\Models\EventType;
use Illuminate\Http\JsonResponse;

class EventTypeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
        $this->middleware('isValidUser', ['except' => ['index']]);
        $this->middleware('keyLowercase', ['only' => ['store', 'update']]);

        // Privileges
        $this->middleware('isProfessor', ['only' => ['store', 'show', 'getAllEventTypes']]);
        $this->middleware('isCoordinator', ['only' => ['update', 'destroy']]);
    }

    public function index() : JsonResponse
    {
        $result = EventType::whereNotNull('name')->orderBy('name', 'asc')->get(['name']);

        return $this->sendResponse($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreEventTypeRequest  $request
     * @return JsonResponse
     */
    public function store(StoreEventTypeRequest $request) : JsonResponse
    {
        $eventType = EventType::create($request->validated());

        return $this->sendResponse($eventType);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $name) : JsonResponse
    {
        $eventType = EventType::where('name', $name)->first();

        if (empty($eventType))
        {
            return $this->sendError(404, 'The Event Type called '.$name.' was not found.');
        }

        return $this->sendResponse($eventType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEventTypeRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(UpdateEventTypeRequest $request, string $name) : JsonResponse
    {
        $eventType = EventType::where('name', $name)->first();

        if (empty($eventType))
        {
            return $this->sendError(404, 'The Event Type called '.$name.' was not found.');
        }

        $eventType->update($request->all());

        return $this->sendResponse($eventType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $name): JsonResponse
    {
        $eventType = EventType::where('name', $name)->delete();

        if (!$eventType)
        {
            return $this->sendError(404, 'The Event Type called '.$name.' was not found.');
        }

        return $this->sendResponse();
    }


    public function getAllEventTypes(): JsonResponse
    {
        $result = EventType::all();

        return $this->sendResponse($result);
    }
}
