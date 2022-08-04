<?php

namespace App\Http\Requests\EventType;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventTypeRequest extends FormRequest
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
            'name' => 'required',
            'key' => 'required|unique:event_types,key',
            'fields' => 'required',
        ];
    }
}
