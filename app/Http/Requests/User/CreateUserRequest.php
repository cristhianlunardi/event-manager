<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email'  => 'bail|required|email|unique:users,email',
            'password'  => 'required|min:6|confirmed',
            'full_name'  => 'required',
            'birthdate'  => 'required|date_format:d/m/Y',
            'dependency.*'  => 'required|exists:dependencies,name',
            'role.*'  => 'required|exists:roles,name',
            'id_number' => 'nullable',
            'rif' => 'nullable',
            'user_type' => 'required'
        ];
    }
}
