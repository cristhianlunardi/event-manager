<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'startDate' => 'sometimes|date_format:d/m/Y',
            'dependency' => 'required',
            'author' => 'sometimes|required',
            'description' => 'sometimes|required',
            'image' => 'sometimes|required|image',
            'eventType' => 'sometimes|required',
            'eventTypeFields' => 'sometimes|required',
            'additionalFields' => 'sometimes|required',
            'agreements' => 'sometimes|required',
            'participants' => 'sometimes|required',
        ];
    }
}
