<?php

namespace App\Http\Requests\EventType;

use App\Models\EventType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventTypeRequest extends FormRequest
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
        $eventType = EventType::where('name', $this->route('eventType'))->first();

        if (empty($eventType)) return [];

        return [
            'name' => 'required',
            'key' => 'required|unique:event_types,key,'.$eventType->key.',key',
            'fields' => 'required',
        ];
    }
}
