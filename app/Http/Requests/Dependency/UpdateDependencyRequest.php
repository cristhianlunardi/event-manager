<?php

namespace App\Http\Requests\Dependency;

use App\Models\Dependency;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDependencyRequest extends FormRequest
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
        $dependency = Dependency::where('name', $this->route('dependency'))->first();

        if (empty($dependency)) return [];

        return [
            'name' => 'required',
            'key' => 'required|unique:dependencies,key,'.$dependency->key.',key',
        ];
    }
}
