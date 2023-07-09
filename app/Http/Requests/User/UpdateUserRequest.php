<?php

namespace App\Http\Requests\User;

use App\Models\Dependency;
use App\Rules\ValidCurrentUserPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
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
            'email' => 'bail|sometimes|email|unique:users,email,'.Auth::user()->_id.',_id',
            'old_password' => ['sometimes', 'required', 'min:6', new ValidCurrentUserPassword()],
            'password'  => 'required_with:old_password|min:6|different:current_password|confirmed',
            'fullName'  => 'sometimes',
            'birthday'  => 'sometimes|date_format:d/m/Y',
            'isActive' => 'sometimes|Boolean',
            'dependency.*'  => 'required|exists:dependencies,name',
            'role.*'  => 'required|exists:roles,name',
        ];
    }
}
