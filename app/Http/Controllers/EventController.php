<?php

namespace App\Http\Controllers;

use App\Models\Dependency;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEvent;
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
     * @param StoreEvent $request
     * @return JsonResponse
     */
    public function store(StoreEvent $request): JsonResponse
    {
        $myEvent = new Event($request->validated());
        if ($myEvent->image) {
            $imageUrl = $request->file('image')->store('public/eventImages');
            $imageUrl = Storage::url($imageUrl);
            $myEvent->image = $imageUrl;
        }

        $dependency = Dependency::where('key', mb_strtolower($request->dependency))->first();
        if ($dependency) {
            $dependencyId = $dependency->_id;
            $myEvent->dependency = $dependencyId;
        }

        $myEvent->save();

        $myEvent->dependency = $dependency->name;

        return $this->sendResponse($myEvent);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
}
