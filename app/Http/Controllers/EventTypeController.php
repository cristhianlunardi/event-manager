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
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->middleware('validUser', ['except' => ['index', 'show']]);
        $this->middleware('keyLowercase', ['only' => ['store', 'update']]);

        // Privileges
        $this->middleware('isProfessor', ['only' => ['store']]);
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
    public function show(string $id) : JsonResponse
    {
        $dependency = EventType::findOrFail($id);

        return $this->sendResponse($dependency);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEventTypeRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(UpdateEventTypeRequest $request, string $id) : JsonResponse
    {
        $eventType = EventType::findOrFail($id);
        $eventType->fill($request->validated())->save();

        return $this->sendResponse($eventType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        EventType::findOrFail($id)->delete();

        return $this->sendResponse();
    }
}
