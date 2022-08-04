<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEvent;
use const App\DEFAULT_PAGE_SIZE;

class EventController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->middleware('isValidUser', ['except' => ['index', 'show']]);
        // $this->middleware('keyLowercase', ['only' => ['store', 'update']]);

        // Privileges
        $this->middleware('isSecretary', ['only' => ['store']]);
        $this->middleware('isProfessor', ['only' => ['update', 'destroy']]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEvent $request)
    {
        echo $request;
        /*$validated = $request->validate([
            'data' => 'required',
        ]);*/

        /*foreach ($request->data as $element)
        {
            $element->validate([
                "title"  => "required",
                "dependency"  => "required",
                "eventType"  => "required",
            ]);
        }*/

        //echo $request->data;

        /*$data = $request->validate([
            "data.*.title"  => "required",
            "data.*.dependency"  => "required",
            "data.*.eventType"  => "required",
        ]);*/

        /*$validator = Validator::make($request->data->all(), [
            'dependency' => 'required',
            'eventType' => 'required',
        ]);*/

        //print_r($data);
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
     * @param  \Illuminate\Http\Request  $request
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
