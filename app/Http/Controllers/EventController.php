<?php

namespace App\Http\Controllers;

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
        //$result = Event::find('62ddecd0223e0000a7003c82')->getdependency;
        $result = Event::collection('event')->first();
            //select(['title', 'startDate', 'eventType', 'dependency'])->dependency;
            //->orderBy('startDate', 'asc')
            //->paginate($pageSize);

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
        $event = Event::create($request->validated());
        //print($request->file('image'));
        $imageUrl = $request->file('image')->store('public/eventImages');
        $imageUrl = Storage::url($imageUrl);

        $event->image = $imageUrl;
        $event->save();

        return $this->sendResponse($event);
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
}
