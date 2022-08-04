<?php

namespace App\Http\Requests\Role;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
        $role = Role::where('name', $this->route('role'))->first();

        if (empty($role)) return [];

        return [
            'name' => 'required',
            'key' => 'required|unique:roles,key,'.$role->key.',key',
        ];
    }
}
