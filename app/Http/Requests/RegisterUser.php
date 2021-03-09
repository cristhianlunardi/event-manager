<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUser extends FormRequest
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
            "data.*.email"  => "bail|required|unique:users|email",
            "data.*.password"  => "required|min:6",
            "data.*.c_password"  => "required|same:data.*.password",
            "data.*.fullName"  => "required",
            "data.*.birthday"  => "required|date",
            "data.*.dependency"  => "required|exists:event_types,name",
            "data.*.rol"  => "required",
        ];
    }
}
