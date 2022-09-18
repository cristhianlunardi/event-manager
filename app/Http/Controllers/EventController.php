<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\UpdateEventRequest;
use App\Models\Dependency;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Event\StoreEventRequest;
use Illuminate\Support\Facades\Storage;

use const App\DEFAULT_PAGE_SIZE;

class EventController extends ApiController
{
    public function __construct()
    {
        //$this->middleware('auth:api', ['except' => ['index', 'show']]);
        //$this->middleware('isValidUser', ['except' => ['index', 'show']]);
        // $this->middleware('keyLowercase', ['only' => ['store', 'update']]);

        // Privileges
        //$this->middleware('isSecretary', ['only' => ['store']]);
        //$this->middleware('isProfessor', ['only' => ['update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $pageSize = (int)$request->query('page_size', DEFAULT_PAGE_SIZE);
        $result = Event::whereNotNull('title')->orderBy('startDate', 'desc')->paginate($pageSize);

        foreach ($result as $event) {
            $this->prepareEventResponse($event);
        }

        return $this->sendResponse($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEventRequest $request
     * @return JsonResponse
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        $myEvent = new Event($request->validated());
        if ($myEvent->image) {
            $imageUrl = $request->file('image')->store('public/eventImages');
            $imageUrl = Storage::url($imageUrl);
            $myEvent->image = $imageUrl;
        }

        $this->prepareEventToSave($myEvent);
        $myEvent->save();
        $this->prepareEventResponse($myEvent);

        return $this->sendResponse($myEvent);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $event = Event::where('_id', $id)->first();

        if (empty($event))
        {
            return $this->sendError(404, $this->getNotFoundMessage());
        }

        $this->prepareEventResponse($event);

        return $event;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(UpdateEventRequest $request, $id)
    {
        $event = Event::where('_id', $id)->first();

        if (empty($event))
        {
            return $this->sendError(404, $this->getNotFoundMessage());
        }

        $event->fill($request->validated());
        if ($request->dependency)
        {
            $event->dependency = Dependency::getIdFromName($request->dependency);
        }

        if ($request->image)
        {
            $imageUrl = $request->file('image')->store('public/eventImages');
            $imageUrl = Storage::url($imageUrl);
            $event->image = $imageUrl;
        }

        $event->save();
        $this->prepareEventResponse($event);

        return $event;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $event = Event::where('_id', $id)->delete();

        if (empty($event))
        {
            return $this->sendError(404, $this->getNotFoundMessage());
        }

        return $this->sendResponse();
    }

    private function prepareEventResponse(Event $event): Event
    {
        $event->dependency = Dependency::getNameFromId($event->dependency);

        return $event;
    }

    private function prepareEventToSave(Event $event): Event {
        $event->dependency = Dependency::getIdFromName($event->dependency);

        return $event;
    }

    private function getNotFoundMessage()
    {
        return 'The Event was not found.';
    }
}
