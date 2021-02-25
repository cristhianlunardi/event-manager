<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreEvent;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
